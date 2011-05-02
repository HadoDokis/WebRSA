<?php
	class Signalementseps93Controller extends Appcontroller
	{
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
				$count = $this->Signalementep93->Dossierep->find(
					'count',
					array(
						'conditions' => array(
							'Signalementep93.contratinsertion_id' => $contratinsertion_id,
							'Dossierep.themeep' => 'signalementseps93',
							'Dossierep.id NOT IN ( '.$this->Signalementep93->Dossierep->Passagecommissionep->sq(
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
								'table'      => 'signalementseps93',
								'alias'      => 'Signalementep93',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Dossierep.id = Signalementep93.dossierep_id' )
							),
							array(
								'table'      => 'contratsinsertion',
								'alias'      => 'Contratinsertion',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Contratinsertion.id = Signalementep93.contratinsertion_id' )
							),
						),
					)
				);
				$this->assert( empty( $count ), 'error500' );
			}
			else {
				$signalementep93_id = $id;
				$signalementep93 = $this->Signalementep93->find(
					'first',
					array(
						'conditions' => array(
							'Signalementep93.id' => $signalementep93_id
						),
						'contain' => false
					)
				);
				$this->assert( !empty( $signalementep93 ), 'invalidParameter' );
				$contratinsertion_id = $signalementep93['Signalementep93']['contratinsertion_id'];
			}

			// Recherche du CER et vérifications
			$contratinsertion = $this->Signalementep93->Contratinsertion->find(
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

			// TODO: d'un allocataire RSA soumis à droits et devoirs, appartenant à un dossier dont les droits sont ouverts
			$traitable = (
				( $contratinsertion['Contratinsertion']['decision_ci'] == 'V' )
				&& ( strtotime( $contratinsertion['Contratinsertion']['dd_ci'] ) <= mktime() )
				&& ( strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) >= mktime() )
			);
			$this->assert( $traitable, 'error500' );

			if( !empty( $this->data ) ) {
				$this->Signalementep93->Dossierep->begin();

				if( $this->action == 'add' ) {
					$rangpcd = $this->Signalementep93->field( 'rang', array( 'Signalementep93.contratinsertion_id' => $contratinsertion_id ), array( 'Signalementep93.rang DESC' ) );
					$this->data['Signalementep93']['contratinsertion_id'] = $contratinsertion_id;
					$this->data['Signalementep93']['rang'] = ( empty( $rangpcd ) ? 1 : $rangpcd + 1 );

					$this->data['Dossierep']['personne_id'] = $personne_id;
					$this->data['Dossierep']['themeep'] = 'signalementseps93';

					$success = $this->Signalementep93->Dossierep->saveAll( $this->data, array( 'atomic' => false ) );
				}
				else {
					$success = $this->Signalementep93->create();
					$success = $this->Signalementep93->save( $this->data );
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Signalementep93->commit();
					$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Signalementep93->Dossierep->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $signalementep93;
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
			$signalementep93 = $this->Signalementep93->Dossierep->find(
				'first',
				array(
					'fields' => array(
						'Signalementep93.id',
						'Dossierep.id',
						'Passagecommissionep.etatdossierep',
					),
					'conditions' => array(
						'Signalementep93.id' => $id,
						'Dossierep.themeep' => 'signalementseps93',
						'Dossierep.id NOT IN ( '.$this->Signalementep93->Dossierep->Passagecommissionep->sq(
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
							'table'      => 'signalementseps93',
							'alias'      => 'Signalementep93',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossierep.id = Signalementep93.dossierep_id' )
						),
						array(
							'table'      => 'contratsinsertion',
							'alias'      => 'Contratinsertion',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Contratinsertion.id = Signalementep93.contratinsertion_id' )
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

			$this->assert( !empty( $signalementep93 ), 'invalidParameter' );

			$this->Signalementep93->Dossierep->begin();
			$success = $this->Signalementep93->Dossierep->delete( $signalementep93['Dossierep']['id'] );
			$this->_setFlashResult( 'Delete', $success );

			if( $success ) {
				$this->Signalementep93->Dossierep->commit();
			}
			else {
				$this->Signalementep93->Dossierep->rollback();
			}

			$this->redirect( Router::url( $this->referer(), true ) );
		}
	}
?>