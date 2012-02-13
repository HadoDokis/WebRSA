<?php
	class SignalementsepsController extends Appcontroller
	{

		public $uses = array( 'Signalementep93' );

		public $commeDroit = array(
			'add' => 'Signalementseps:edit'
		);

		/**
		*
		*/

		public function beforeFilter() {
			$this->modelClass = 'Signalementep'.Configure::read( 'Cg.departement' );
			parent::beforeFilter();
		}

		/**
		*
		*/

		public function add( $contratinsertion_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit( $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( $this->action == 'add' ) {
				$contratinsertion_id = $id;

				// Il n'existe pas d'autre signalement en cours de traitement pour ce contrat
				$count = $this->{$this->modelClass}->Dossierep->find(
					'count',
					array(
						'conditions' => array(
							$this->modelClass.'.contratinsertion_id' => $contratinsertion_id,
							'Dossierep.themeep' => Inflector::tableize( $this->modelClass ),
							'Dossierep.id NOT IN ( '.$this->{$this->modelClass}->Dossierep->Passagecommissionep->sq(
								array(
									'alias' => 'passagescommissionseps',
									'fields' => array(
										'passagescommissionseps.dossierep_id'
									),
									'conditions' => array(
										'passagescommissionseps.dossierep_id = Dossierep.id',
										'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
									)
								)
							).' )'
						),
						'joins' => array(
							array(
								'table'      => Inflector::tableize( $this->modelClass ),
								'alias'      => $this->modelClass,
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( "Dossierep.id = {$this->modelClass}.dossierep_id" )
							),
							array(
								'table'      => 'contratsinsertion',
								'alias'      => 'Contratinsertion',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( "Contratinsertion.id = {$this->modelClass}.contratinsertion_id" )
							),
						),
					)
				);
				$this->assert( empty( $count ), 'error500' );
			}
			else {
				$signalementep_id = $id;
				$signalementep = $this->{$this->modelClass}->find(
					'first',
					array(
						'conditions' => array(
							$this->modelClass.'.id' => $signalementep_id
						),
						'contain' => false
					)
				);
				$this->assert( !empty( $signalementep ), 'invalidParameter' );
				$contratinsertion_id = $signalementep[$this->modelClass]['contratinsertion_id'];
			}

			// Recherche du CER et vérifications
			$contratinsertion = $this->{$this->modelClass}->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'contain' => false
				)
			);
			$this->assert( !empty( $contratinsertion ), 'invalidParameter' );

			$personne_id = $contratinsertion['Contratinsertion']['personne_id'];

			$erreursCandidatePassage = $this->{$this->modelClass}->Dossierep->erreursCandidatePassage( $personne_id );

			$dureeTolerance = Configure::read( $this->modelClass.'.dureeTolerance' );

			$traitable = (
				( $contratinsertion['Contratinsertion']['decision_ci'] == 'V' )
				&& ( strtotime( $contratinsertion['Contratinsertion']['dd_ci'] ) <= mktime() )
				&& ( strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) + ( $dureeTolerance * 24 * 60 * 60 ) >= mktime() )
				&& empty( $erreursCandidatePassage )
			);
			$this->assert( $traitable, 'error500' );

			if( !empty( $this->data ) ) {

				if ( isset( $this->params['form']['Cancel'] ) ) {
					$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
				}

				$this->{$this->modelClass}->Dossierep->begin();

				if( $this->action == 'add' ) {
					$rangpcd = $this->{$this->modelClass}->field( 'rang', array( $this->modelClass.'.contratinsertion_id' => $contratinsertion_id ), array( $this->modelClass.'.rang DESC' ) );
					$this->data[$this->modelClass]['contratinsertion_id'] = $contratinsertion_id;
					$this->data[$this->modelClass]['rang'] = ( empty( $rangpcd ) ? 1 : $rangpcd + 1 );

					$this->data['Dossierep']['personne_id'] = $personne_id;
					$this->data['Dossierep']['themeep'] = Inflector::tableize( $this->modelClass );

					$success = $this->{$this->modelClass}->Dossierep->saveAll( $this->data, array( 'atomic' => false ) );
				}
				else {
					$success = $this->{$this->modelClass}->create( $this->data );
					$success = $this->{$this->modelClass}->save();
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->{$this->modelClass}->commit();
					$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->{$this->modelClass}->Dossierep->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $signalementep;
			}

			$this->set( 'personne_id', $personne_id );
			$this->render( null, null, 'add_edit' );
		}

		/**
		* Permet de supprimer un signalement SSI celui-ci:
		*	- existe
		*	- n'est pas associé à une commission d'EP
		*/

		public function delete( $id ) {
			$signalementep = $this->{$this->modelClass}->Dossierep->find(
				'first',
				array(
					'fields' => array(
						$this->modelClass.'.id',
						'Dossierep.id',
						'Passagecommissionep.etatdossierep',
					),
					'conditions' => array(
						$this->modelClass.'.id' => $id,
						'Dossierep.themeep' => Inflector::tableize( $this->modelClass ),
						'Dossierep.id NOT IN ( '.$this->{$this->modelClass}->Dossierep->Passagecommissionep->sq(
							array(
								'alias' => 'passagescommissionseps',
								'fields' => array(
									'passagescommissionseps.dossierep_id'
								),
								'conditions' => array(
									'passagescommissionseps.dossierep_id = Dossierep.id'
								)
							)
						).' )'
					),
					'joins' => array(
						array(
							'table'      => Inflector::tableize( $this->modelClass ),
							'alias'      => $this->modelClass,
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.id = {$this->modelClass}.dossierep_id" )
						),
						array(
							'table'      => 'contratsinsertion',
							'alias'      => 'Contratinsertion',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Contratinsertion.id = {$this->modelClass}.contratinsertion_id" )
						),
						array(
							'table'      => 'passagescommissionseps',
							'alias'      => 'Passagecommissionep',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Dossierep.id = Passagecommissionep.dossierep_id' )
						),
					),
				)
			);

			$this->assert( !empty( $signalementep ), 'invalidParameter' );

			$this->{$this->modelClass}->Dossierep->begin();
			$success = $this->{$this->modelClass}->Dossierep->delete( $signalementep['Dossierep']['id'] );
			$this->_setFlashResult( 'Delete', $success );

			if( $success ) {
				$this->{$this->modelClass}->Dossierep->commit();
			}
			else {
				$this->{$this->modelClass}->Dossierep->rollback();
			}

			$this->redirect( Router::url( $this->referer(), true ) );
		}
	}
?>