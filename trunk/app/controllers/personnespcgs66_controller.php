<?php
	class Personnespcgs66Controller extends AppController
	{
		public $name = 'Personnespcgs66';
		public $uses = array( 'Personnepcg66', 'Option', 'Dossierpcg66' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Default2', 'Fileuploader' );
		public $components = array( 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Personnespcgs66:index',
			'add' => 'Personnespcgs66:edit'
		);

		/**
		*
		*/

		protected function _setOptions() {
			$options = array();

			$this->set( 'statutlist', $this->Dossierpcg66->Personnepcg66->Statutpdo->find( 'list', array( 'order' => 'Statutpdo.libelle ASC' ) ) );
			$this->set( 'situationlist', $this->Dossierpcg66->Personnepcg66->Situationpdo->find( 'list', array( 'order' => 'Situationpdo.libelle ASC' ) ) );
			$this->set( compact( 'options' ) );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);

			// Récupération des codes ROM stockés en paramétrage
				$options['Coderomesecteurdsp66'] = ClassRegistry::init( 'Libsecactderact66Secteur' )->find(
					'list',
					array(
						'contain' => false,
						'order' => array( 'Libsecactderact66Secteur.code' )
					)
				);
				$codesromemetiersdsps66 = ClassRegistry::init( 'Libderact66Metier' )->find(
					'all',
					array(
						'contain' => false,
						'order' => array( 'Libderact66Metier.code' )
					)
				);
				foreach( $codesromemetiersdsps66 as $coderomemetierdsp66 ) {
					$options['Coderomemetierdsp66'][$coderomemetierdsp66['Libderact66Metier']['coderomesecteurdsp66_id'].'_'.$coderomemetierdsp66['Libderact66Metier']['id']] = $coderomemetierdsp66['Libderact66Metier']['code'].'. '.$coderomemetierdsp66['Libderact66Metier']['name'];
				}
			$this->set( compact( 'options' ) );


		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Personnepcg66->field( 'dossierpcg66_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $id ) );
			}

			$personnes = array();
			$premierAjout = false;
			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$dossierpcg66_id = $id;

				$dossierpcg66 = $this->Dossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Dossierpcg66.id' => $id
						),
						'contain' => array(
							'Personnepcg66' => array(
								'Statutpdo',
								'Situationpdo'
							)
						)
					)
				);
				$this->set( 'dossierpcg66', $dossierpcg66 );

				if ( !empty( $dossierpcg66['Dossierpcg66']['bilanparcours66_id'] ) ) {
					$personne = $this->Personnepcg66->Dossierpcg66->Bilanparcours66->Orientstruct->Personne->find(
						'first',
						array(
							'fields' => array(
								'Personne.id',
								'Bilanparcours66.examenaudition',
								'Bilanparcours66.examenauditionpe'
							),
							'conditions' => array(
								'Bilanparcours66.id' => $dossierpcg66['Dossierpcg66']['bilanparcours66_id']
							),
							'joins' => array(
								array(
									'table' => 'orientsstructs',
									'alias' => 'Orientstruct',
									'type' => 'INNER',
									'conditions' => array( 'Orientstruct.personne_id = Personne.id' )
								),
								array(
									'table' => 'bilansparcours66',
									'alias' => 'Bilanparcours66',
									'type' => 'INNER',
									'conditions' => array( 'Bilanparcours66.orientstruct_id = Orientstruct.id' )
								)
							),
							'contain' => false
						)
					);

					if ( empty( $dossierpcg66['Personnepcg66'] ) ) {
						$premierAjout = true;
					}
					else {
						$dejaAjoute = false;
						foreach( $dossierpcg66['Personnepcg66'] as $personnepcg66 ) {
							if ( $personnepcg66['id'] == $personne['Personne']['id'] ) {
								$dejaAjoute = true;
							}
						}
						if ( !$dejaAjoute ) {
							$premierAjout = true;
						}
					}

					if ( $premierAjout ) {
						$situationspdos = array();
						if ( $personne['Bilanparcours66']['examenaudition'] == 'DOD' || $personne['Bilanparcours66']['examenauditionpe'] == 'noninscriptionpe' ) {
							$situationspdos = $this->Personnepcg66->Situationpdo->find(
								'all',
								array(
									'conditions' => array(
										'Situationpdo.nc' => '1'
									),
									'contain' => false
								)
							);
						}
						elseif ( $personne['Bilanparcours66']['examenaudition'] == 'DRD' || $personne['Bilanparcours66']['examenauditionpe'] == 'radiationpe' ) {
							$situationspdos = $this->Personnepcg66->Situationpdo->find(
								'all',
								array(
									'conditions' => array(
										'Situationpdo.nr' => '1'
									),
									'contain' => false
								)
							);
						}

						$motif = array();
						foreach( $situationspdos as $situationpdo ) {
							$motif['Situationpdo']['Situationpdo'][] = $situationpdo['Situationpdo']['id'];
						}

						$personnepcg66 = array_merge(
							array(
								'Personnepcg66' => array(
									'personne_id' => $personne['Personne']['id']
								)
							),
							$motif
						);
					}
				}

				$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );
				$dossier_id = $this->Dossierpcg66->Foyer->dossierId( $foyer_id );

				//Liste des personnes appartenant au foyer dont le dossier fait question
				$personnes = $this->Personnepcg66->Personne->find(
					'list',
					array(
						'fields' => array( 'nom_complet' ),
						'conditions' => array(
							'Personne.foyer_id' => $foyer_id,
							'Personne.id IN (
								'.$this->Personnepcg66->Personne->Prestation->sq(
									array(
										'alias' => 'prestations',
										'fields' => array( 'prestations.personne_id' ),
										'conditions' => array(
											'prestations.natprest = \'RSA\'',
											'prestations.rolepers' => array( 'DEM', 'CJT' )
										),
										'contain' => false
									)
								).
							' )',
							'Personne.id NOT IN (
								'.$this->Personnepcg66->sq(
									array(
										'alias' => 'personnespcgs66',
										'fields' => array( 'personnespcgs66.personne_id' ),
										'conditions' => array(
											'personnespcgs66.dossierpcg66_id' => $id
										),
										'contain' => false
									)
								).
							' )',
						),
					)
				);
			}
			else if( $this->action == 'edit' ) {
				$personnepcg66_id = $id;
				$personnepcg66 = $this->Personnepcg66->find(
					'first',
					array(
						'conditions' => array(
							'Personnepcg66.id' => $personnepcg66_id
						),
						'contain' => array(
							'Statutpdo',
							'Situationpdo'
						)
					)
				);
				$this->assert( !empty( $personnepcg66 ), 'invalidParameter' );
				$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );

				$dossierpcg66 = $this->Dossierpcg66->findById( $dossierpcg66_id, null, null, -1 );
				$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );
				$dossier_id = $this->Dossierpcg66->Foyer->dossierId( $foyer_id );

				//Liste des personnes appartenant au foyer dont le dossier fait question
				$personnes = $this->Personnepcg66->Personne->find(
					'list',
					array(
						'fields' => array( 'nom_complet' ),
						'conditions' => array(
							'Personne.foyer_id' => $foyer_id,
							'Personne.id IN (
								'.$this->Personnepcg66->Personne->Prestation->sq(
									array(
										'alias' => 'prestations',
										'fields' => array( 'prestations.personne_id' ),
										'conditions' => array(
											'prestations.natprest = \'RSA\'',
											'prestations.rolepers' => array( 'DEM', 'CJT' )
										),
										'contain' => false
									)
								).' )',
							'Personne.id NOT IN (
								'.$this->Personnepcg66->sq(
									array(
										'alias' => 'personnespcgs66',
										'fields' => array( 'personnespcgs66.personne_id' ),
										'conditions' => array(
											'personnespcgs66.dossierpcg66_id' => $dossierpcg66_id,
											'personnespcgs66.id NOT' => $personnepcg66_id
										),
										'contain' => false
									)
								).' )',
							),
					)
				);
			}
			$this->set( compact( 'personnes', 'dossierpcg66' ) );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			//Gestion des jetons
			$this->Personnepcg66->begin();
			$dossier_id = $this->Personnepcg66->Dossierpcg66->Foyer->dossierId( $foyer_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Personnepcg66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ){

				$personnepcg66 = $this->data['Personnepcg66'];
				$situationspdos = $this->data['Situationpdo'];
				$statutspdos = $this->data['Statutpdo'];

				$this->Personnepcg66->create( $personnepcg66 );
				$success = $this->Personnepcg66->save();

				if ( empty( $this->data['Situationpdo']['Situationpdo'] ) ) {
					$success = false;
					$this->Personnepcg66->invalidate( 'Situationpdo.Situationpdo', 'Il est obligatoire de saisir au moins un motif de décision pour la personne.' );
				}
				if ( empty( $this->data['Statutpdo']['Statutpdo'] ) ) {
					$success = false;
					$this->Personnepcg66->invalidate( 'Statutpdo.Statutpdo', 'Il est obligatoire de saisir au moins un statut pour la personne.' );
				}

				
				if( $success ) {
					foreach( array( 'situationspdos', 'statutspdos' ) as $tableliee ) {
						$modelelie = Inflector::classify( $tableliee );
						$modeleliaison = Inflector::classify( "personnespcgs66_{$tableliee}" );
						$foreignkey = Inflector::singularize( $tableliee ).'_id';
						$records = $this->Personnepcg66->{$modeleliaison}->find(
							'list',
							array(
								'fields' => array( "{$modeleliaison}.id", "{$modeleliaison}.{$foreignkey}" ),
								'conditions' => array(
									"{$modeleliaison}.personnepcg66_id" => $this->Personnepcg66->id
								)
							)
						);

						$oldrecordsids = array_values( $records );
						$nouveauxids = Set::filter( Set::extract( "/{$modelelie}", $$tableliee ) );

						if ( empty( $nouveauxids ) ) {
							$this->Personnepcg66->{$modelelie}->invalidate( $modelelie, 'Merci de cocher au moins une case' );
							$success = false;
						}
						else {
							// En moins -> Supprimer
							$idsenmoins = array_diff( $oldrecordsids, $nouveauxids );
							if( !empty( $idsenmoins ) ) {
								$success = $this->Personnepcg66->{$modeleliaison}->deleteAll(
									array(
										"{$modeleliaison}.personnepcg66_id" => $this->Personnepcg66->id,
										"{$modeleliaison}.{$foreignkey}" => $idsenmoins
									)
								) && $success;
							}

							// En plus -> Ajouter
							$idsenplus = array_diff( $nouveauxids, $oldrecordsids );
							if( !empty( $idsenplus ) ) {
								foreach( $idsenplus as $idenplus ) {
									$record = array(
										$modeleliaison => array(
											"personnepcg66_id" => $this->Personnepcg66->id,
											"{$foreignkey}" => $idenplus
										)
									);

									$this->Personnepcg66->{$modeleliaison}->create( $record );
									$success = $this->Personnepcg66->{$modeleliaison}->save() && $success;
								}
							}
						}
					}

					if( $success ) {
						$this->Jetons->release( $dossier_id );
						$this->Personnepcg66->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array(  'controller' => 'dossierspcgs66','action' => 'edit', $dossierpcg66_id ) );
					}
					else {
						$this->Personnepcg66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else{
				if( $this->action == 'edit' || $premierAjout ) {
					$this->data = $personnepcg66;
				}
			}
			$this->Personnepcg66->rollback(); //FIXME

			$this->_setOptions();

			$this->set( compact( 'foyer_id', 'dossier_id', 'dossierpcg66_id', 'personnepcg66_id' ) );
			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );

			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		function view( $id = null ) {

			$personnepcg66 = $this->Personnepcg66->find(
				'first',
				array(
					'conditions' => array(
						'Personnepcg66.id' => $id
					),
					'contain' => array(
						'Statutpdo',
						'Situationpdo',
						'Personne' => array(
							'fields' => array(
								'Personne.qual',
								'Personne.nom',
								'Personne.prenom',
							)
						),
					)
				)
			);
			$this->assert( !empty( $personnepcg66 ), 'invalidParameter' );

			$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );
			$dossierpcg66 = $this->Dossierpcg66->findById( $dossierpcg66_id, null, null, -1 );
			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $dossierpcg66_id ) );
			}

			$this->_setOptions();
			$this->set( compact( 'personnepcg66', 'foyer_id' ) );

			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

	}
?>