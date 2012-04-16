<?php
	class Decisionsdossierspcgs66Controller extends AppController
	{
		public $name = 'Decisionsdossierspcgs66';
		/**
		* @access public
		*/

		public $components = array( 'Default', 'Gedooo.Gedooo' );

		public $helpers = array( 'Default2', 'Ajax', 'Fileuploader' );
		public $uses = array( 'Decisiondossierpcg66', 'Option', 'Pdf'  );

		public $aucunDroit = array( 'ajaxproposition' );
		public $commeDroit = array(
			'view' => 'Decisionsdossierspcgs66:index',
			'add' => 'Decisionsdossierspcgs66:edit'
		);

		/**
		*
		*/

		protected function _setOptions() {
			$options = $this->Decisiondossierpcg66->enums();
			$options = array_merge(
				$options,
				$this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->Decisiontraitementpcg66->enums(),
				$this->Decisiondossierpcg66->Dossierpcg66->Decisiondefautinsertionep66->enums()
			);
			$listdecisionpdo = $this->Decisiondossierpcg66->Decisionpdo->find( 'list'/*, array( 'fields' => array( 'Decisionpdo.name' )*/ );
			$typersapcg66 = $this->Decisiondossierpcg66->Typersapcg66->find( 'list' );

			$compofoyerpcg66 = $this->Decisiondossierpcg66->Compofoyerpcg66->find( 'list' );
			
			$this->set( compact( 'options', 'listdecisionpdo', 'typersapcg66', 'compofoyerpcg66' ) );
		}


		
		
		/**
		*	Affichage de la proposition du 
		*/

		public function ajaxproposition() {
			Configure::write( 'debug', 0 );

			$decisionpcg66_id = Set::extract( $this->params, 'form.decisionpcg66_id' );
			
			$data = array(
				'defautinsertion' => Set::extract( $this->params, 'form.defautinsertion' ),
				'compofoyerpcg66_id' => Set::extract( $this->params, 'form.compofoyerpcg66_id' ),
				'recidive' => Set::extract( $this->params, 'form.recidive' ),
				'phase' => Set::extract( $this->params, 'form.phase' )
			);

			$questionspcg = array();

			$calculpossible = true;
			// Nous manque-t'il au moins une valeur permettant de faire le calcul ?
			foreach( $data as $key => $value ) {
				if( is_null( $value ) || $value == '' ) {
					$calculpossible = false;
				}
			}

			// On a toutes les valeurs nécessaires pour faire la calcul
			if( $calculpossible ) {
				$questionspcg = $this->Decisiondossierpcg66->Decisionpcg66->Questionpcg66->find(
					'list',
					array(
						'fields' => array( 'Decisionpcg66.id', 'Decisionpcg66.name' ),
						'conditions' => array(
							'Questionpcg66.defautinsertion' => $data['defautinsertion'],
							'Questionpcg66.compofoyerpcg66_id' => $data['compofoyerpcg66_id'],
							'Questionpcg66.recidive' => $data['recidive'],
							'Questionpcg66.phase' => $data['phase']
						),
						'contain' => false,
						'joins' => array(
							$this->Decisiondossierpcg66->Decisionpcg66->Questionpcg66->join( 'Decisionpcg66' )
						)
					)
				);
			}
			
			if( !empty( $decisionpcg66_id ) ) {
				$this->data['Decisiondossierpcg66']['decisionpcg66_id'] = $decisionpcg66_id;
			}

			$this->set( compact( 'questionspcg', 'calculpossible' ) );
			$this->render( 'ajaxproposition', 'ajax' );
		}

		
		
		
		
		
		
		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}
		}
		
		/** ********************************************************************
		*
		*** *******************************************************************/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->Decisiondossierpcg66->begin();

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$dossierpcg66_id = $id;

				$dossierpcg66 = $this->Decisiondossierpcg66->Dossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Dossierpcg66.id' => $id
						),
						'contain' => array(
							'Decisiondossierpcg66' => array(
								'Decisionpdo',
								'order' => array( 'Decisiondossierpcg66.created DESC' ),
							),
							'Decisiondefautinsertionep66' => array(
								'Passagecommissionep' => array(
									'Dossierep' => array(
										'Defautinsertionep66' => array(
											'Bilanparcours66'
										)
									)
								)
							),
							'Fichiermodule'
						)
					)
				);
				$this->set( 'dossierpcg66', $dossierpcg66 );

				$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );
				$dossier_id = $this->Decisiondossierpcg66->Dossierpcg66->Foyer->dossierId( $foyer_id );
			}
			else if( $this->action == 'edit' ) {
				$decisiondossierpcg66_id = $id;
				$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Decisiondossierpcg66.id' => $decisiondossierpcg66_id
						),
						'order' => array( 'Decisiondossierpcg66.created DESC' ),
						'contain' => array( 'Typersapcg66' )
					)
				);
				$this->assert( !empty( $decisiondossierpcg66 ), 'invalidParameter' );
				$dossierpcg66_id = Set::classicExtract( $decisiondossierpcg66, 'Decisiondossierpcg66.dossierpcg66_id' );
				// FIXME: une fonction avec la partie du add ci-dessus
				$dossierpcg66 = $this->Decisiondossierpcg66->Dossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Dossierpcg66.id' => $dossierpcg66_id
						),
						'contain' => array(
							'Decisiondossierpcg66' => array(
								'conditions' => array( 'Decisiondossierpcg66.id <>' => $id ),
								'order' => array( 'Decisiondossierpcg66.created DESC' ),
								'Decisionpdo'
							),
							'Decisiondefautinsertionep66' => array(
								'Passagecommissionep' => array(
									'Dossierep' => array(
										'Defautinsertionep66' => array(
											'Bilanparcours66'
										)
									)
								)
							),
							'Fichiermodule'
						)
					)
				);

				$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );
				$dossier_id = $this->Decisiondossierpcg66->Dossierpcg66->Foyer->dossierId( $foyer_id );;
			}

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );


			if( !empty( $dossierpcg66['Decisiondefautinsertionep66']['decision'] ) ) {
				if( $dossierpcg66['Decisiondefautinsertionep66']['decision'] != 'maintien' ) {
					$decisiondossierpcg66_decision = $dossierpcg66['Decisiondefautinsertionep66']['decision'].'_'.$dossierpcg66['Decisiondefautinsertionep66']['Passagecommissionep']['Dossierep']['Defautinsertionep66']['Bilanparcours66']['proposition'];
				}
				else {
					$decisiondossierpcg66_decision = $dossierpcg66['Decisiondefautinsertionep66']['Passagecommissionep']['Dossierep']['Defautinsertionep66']['Bilanparcours66']['examenaudition'];
					$proposition = $dossierpcg66['Decisiondefautinsertionep66']['Passagecommissionep']['Dossierep']['Defautinsertionep66']['Bilanparcours66']['proposition'];
					if( $decisiondossierpcg66_decision == 'DOD' ) {
						$decisiondossierpcg66_decision = 'suspensiondefaut';
					}
					else {
						$decisiondossierpcg66_decision = 'suspensionnonrespect';
					}
					$decisiondossierpcg66_decision = $decisiondossierpcg66_decision.'_'.$proposition;
					
				}
				if( $decisiondossierpcg66_decision == 'suspensiondefaut_audition' ) {
					if( empty( $dossierpcg66['Decisiondefautinsertionep66']['Passagecommissionep']['Dossierep']['Defautinsertionep66']['Bilanparcours66']['orientstruct_id'] ) ) {
						$decisiondossierpcg66_decision = "{$decisiondossierpcg66_decision}_nonorientation";
					}
					else {
						$decisiondossierpcg66_decision = "{$decisiondossierpcg66_decision}_orientation";
					}
				}

				$this->set( 'decisiondossierpcg66_decision', $decisiondossierpcg66_decision ); // FIXME: pour le add
			}

			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'dossierpcg66_id', $dossierpcg66_id );
			$this->set( 'foyer_id', $foyer_id );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			if ( !$this->Jetons->check( $dossier_id ) ) {
				$this->Decisiondossierpcg66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );


			if ( !empty( $this->data ) ) {
// debug( $this->data );
// die();
				if( $this->Decisiondossierpcg66->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Decisiondossierpcg66->save( $this->data );
					if( !empty( $this->data['Decisiondossierpcg66Decisionpersonnepcg66'][0]['decisionpersonnepcg66_id'] ) ){
						foreach( $this->data['Decisiondossierpcg66Decisionpersonnepcg66'] as $joinTable ) {
							if ( isset( $this->data['Decisiondossierpcg66']['validationproposition'] ) && $this->data['Decisiondossierpcg66']['validationproposition'] == 'O' ) {
								$this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->Decisionpersonnepcg66->id = $joinTable['decisionpersonnepcg66_id'] && $saved;
	// 							$saved = $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->Decisiontraitementpcg66->saveField( 'actif', '0' ) && $saved;
							}
							if ( $this->action == 'add' ) {
								$joinTable['decisiondossierpcg66_id'] = $this->Decisiondossierpcg66->id;
								$this->Decisiondossierpcg66->Decisiondossierpcg66Decisionpersonnepcg66->create( $joinTable );
								$saved = $this->Decisiondossierpcg66->Decisiondossierpcg66Decisionpersonnepcg66->save();
							}
						}
					}

					if( $saved ) {
						$saved = $this->Decisiondossierpcg66->Dossierpcg66->updateEtatViaDecisionFoyer( $this->Decisiondossierpcg66->id ) && $saved;
					}

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Decisiondossierpcg66->commit(); // FIXME
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $dossierpcg66_id ) );
					}
					else {
						$this->Decisiondossierpcg66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Decisiondossierpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $decisiondossierpcg66;
			}

// 			if ( $this->action == 'add' ) {
// 				$joinsEdit = array();
// 				$traitementsConditions = array(
// 					'Traitementpcg66.annule' => 'N',
// 					'Traitementpcg66.id IN ('.$this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->Decisiontraitementpcg66->sq(
// 						array(
// 							'alias' => 'decisionstraitementspcgs66',
// 							'fields' => array(
// 								'decisionstraitementspcgs66.traitementpcg66_id'
// 							),
// 							'conditions' => array(
// 								'decisionstraitementspcgs66.id IN (
// 									(
// 										SELECT
// 												dernieredecisionstraitementspcgs66.id
// 											FROM
// 												decisionstraitementspcgs66 AS dernieredecisionstraitementspcgs66
// 											WHERE
// 												dernieredecisionstraitementspcgs66.traitementpcg66_id = Traitementpcg66.id
// 											ORDER BY dernieredecisionstraitementspcgs66.created DESC
// 											LIMIT 1
// 									)
// 									INTERSECT (
// 										SELECT
// 												decisionstraitementspcgs66conforme.id
// 											FROM
// 												decisionstraitementspcgs66 AS decisionstraitementspcgs66conforme
// 											WHERE
// 												decisionstraitementspcgs66conforme.traitementpcg66_id = Traitementpcg66.id
// 												AND decisionstraitementspcgs66conforme.actif = \'1\'
// 									)
// 								)'
// 							)
// 						)
// 					).' )'
// 				);
// 				$decisionsTraitementsConditions = array(
// 					'Decisiontraitementpcg66.traitementpcg66_id = Traitementpcg66.id',
// 					'Decisiontraitementpcg66.actif' => 1
// 				);
// 			}
// 			else {
// 				$joinsEdit = array(
// 					'table' => 'decisionsdossierspcgs66_decisionstraitementspcgs66',
// 					'alias' => 'Decisiondossierpcg66Decisionpersonnepcg66',
// 					'type' => 'INNER',
// 					'conditions' => array(
// 						'Decisiondossierpcg66Decisionpersonnepcg66.decisiontraitementpcg66_id = Decisiontraitementpcg66.id',
// 						'Decisiondossierpcg66Decisionpersonnepcg66.decisiondossierpcg66_id' => $decisiondossierpcg66_id
// 					)
// 				);
// 				$traitementsConditions = array();
// 				$decisionsTraitementsConditions = array(
// 					'Decisiontraitementpcg66.traitementpcg66_id = Traitementpcg66.id'
// 				);
// 			}
// 
// 			$joins = array_merge( Set::filter(
// 				array(
// 					array(
// 						'table' => 'personnes',
// 						'alias' => 'Personne',
// 						'type' => 'INNER',
// 						'conditions' => array(
// 							'Personne.id = Personnepcg66.personne_id'
// 						)
// 					),
// 					array(
// 						'table' => 'traitementspcgs66',
// 						'alias' => 'Traitementpcg66',
// 						'type' => 'INNER',
// 						'conditions' => array_merge( Set::filter(
// 							array(
// 								'Personnepcg66.id = Traitementpcg66.personnepcg66_id'
// 							),
// 							$traitementsConditions
// 						) )
// 					),
// 					array(
// 						'table' => 'descriptionspdos',
// 						'alias' => 'Descriptionpdo',
// 						'type' => 'INNER',
// 						'conditions' => array(
// 							'Descriptionpdo.id = Traitementpcg66.descriptionpdo_id'
// 						)
// 					),
// 					array(
// 						'table' => 'decisionstraitementspcgs66',
// 						'alias' => 'Decisiontraitementpcg66',
// 						'type' => 'INNER',
// 						'conditions' => $decisionsTraitementsConditions
// 					)
// 				),
// 				array( $joinsEdit )
// 			) );
			$personnespcgs66 = $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->find(
				'all',
				array(
					'conditions' => array(
						'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
					),
					'contain' => array(
						'Statutpdo',
						'Situationpdo',
						'Personne',
						'Traitementpcg66'
					)
				)
			);

			// avistechniquemodifiable, validationmodifiable
			$avistechniquemodifiable = $validationmodifiable = false;
			switch( $dossierpcg66['Dossierpcg66']['etatdossierpcg'] ) {
				case 'attavistech':
					$avistechniquemodifiable = ( $this->action == 'edit' );
					break;
				case 'attval':
				case 'decisionvalid':
				case 'decisionnonvalid':
				case 'decisionnonvalidretouravis':
				case 'decisionvalidretouravis':
				case 'attpj':
				case 'atttransmisop':
					$avistechniquemodifiable = ( $this->action == 'edit' );
					$validationmodifiable  = ( $this->action == 'edit' );
				break;
			}

			$this->set( compact( 'personnespcgs66', 'dossierpcg66', 'decisiondossierpcg66', 'avistechniquemodifiable', 'validationmodifiable' ) );



			$this->Decisiondossierpcg66->commit();
			$this->_setOptions();

			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*   Enregistrement du courrier de proposition lors de l'enregistrement de la proposition
		*/
		public function decisionproposition( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$pdf = $this->Decisiondossierpcg66->getPdfDecision( $id );
// debug($pdf);
// die();
			if( $pdf ) {
				$success = true;

				if( isset( $this->params['named']['save'] ) && $this->params['named']['save'] ) {
					$this->Decisiondossierpcg66->begin();
					$success = $this->Decisiondossierpcg66->updateDossierpcg66Dateimpression( $id );
					if( $success ) {
						$this->Decisiondossierpcg66->commit();
					}
					else {
						$this->Decisiondossierpcg66->rollback();
					}
				}

				if( $success ) {
					$this->Gedooo->sendPdfContentToClient( $pdf, 'Décision' );
				}
			}

			$this->Session->setFlash( 'Impossible de générer la décision', 'default', array( 'class' => 'error' ) );
			$this->redirect( $this->referer() );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			/*$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Descriptionpdo.name',
						'Decisionpdo.libelle',
						'Decisiondossierpcg66.dossierpcg66_id',
						'Decisiondossierpcg66.commentaire',
						'Decisiondossierpcg66.avistechnique',
						'Decisiondossierpcg66.dateavistechnique',
						'Decisiondossierpcg66.commentaireavistechnique',
						'Decisiondossierpcg66.validationproposition',
						'Decisiondossierpcg66.datevalidation',
						'Decisiondossierpcg66.commentairevalidation',
						'Decisiondossierpcg66.decisionpdo_id'
					),
					'conditions' => array(
						'Decisiondossierpcg66.id' => $id
					),
					'joins' => array(
						array(
							'table' => 'dossierspcgs66',
							'alias' => 'Dossierpcg66',
							'type' => 'INNER',
							'conditions' => array(
								'Dossierpcg66.id = Decisiondossierpcg66.dossierpcg66_id'
							)
						),
						array(
							'table' => 'personnespcgs66',
							'alias' => 'Personnepcg66',
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Dossierpcg66.id = Personnepcg66.dossierpcg66_id'
							)
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Dossierpcg66.foyer_id' )
						),
						array(
							'table' => 'personnes',
							'alias' => 'Personne',
							'type' => 'INNER',
							'conditions' => array(
								'Personne.foyer_id = Foyer.id'
							)
						),
						array(
							'table' => 'decisionspdos',
							'alias' => 'Decisionpdo',
							'type' => 'INNER',
							'conditions' => array(
								'Decisionpdo.id = Decisiondossierpcg66.decisionpdo_id'
							)
						),
						array(
							'table' => 'traitementspcgs66',
							'alias' => 'Traitementpcg66',
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Personnepcg66.id = Traitementpcg66.personnepcg66_id'
							)
						),
						array(
							'table' => 'descriptionspdos',
							'alias' => 'Descriptionpdo',
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Descriptionpdo.id = Traitementpcg66.descriptionpdo_id'
							)
						),
						array(
							'table' => 'personnespcgs66_situationspdos',
							'alias' => 'Personnepcg66Situationpdo',
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Personnepcg66Situationpdo.personnepcg66_id = Personnepcg66.id'
							)
						)
					),
					'contain' => false
				)
			);*/

			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Decisiondossierpcg66->fields()
					),
					'conditions' => array(
						'Decisiondossierpcg66.id' => $id
					),
					'contain' => array(
						'Decisionpdo',
						'Dossierpcg66' => array(
							'Personnepcg66',
							'Foyer',
							'Fichiermodule'
						)
					)
				)
			);
			
// 		debug($decisiondossierpcg66);	
			$this->assert( !empty( $decisiondossierpcg66 ), 'invalidParameter' );

			$this->set( 'dossier_id', $this->Decisiondossierpcg66->dossierId( $id ) );

			// Retour à la page d'édition de la PDO
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', Set::classicExtract( $decisiondossierpcg66, 'Decisiondossierpcg66.dossierpcg66_id' ) ) );
			}

			$options = $this->Decisiondossierpcg66->enums();
			$this->set( compact( 'decisiondossierpcg66') );
			$this->_setOptions();

			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$decisiondossierpcg66['Dossierpcg66']['foyer_id'] );
		}

		/**
		* Suppression de la proposition de décision
		*/

	public function delete( $id ) {
			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisiondossierpcg66.id' => $id
					),
					'contain' => array(
						'Dossierpcg66'
					)
				)
			);
			$dossierpcg66_id = Set::classicExtract( $decisiondossierpcg66, 'Dossierpcg66.id' );
			$etatdossierpcg = Set::classicExtract( $decisiondossierpcg66, 'Dossierpcg66.etatdossierpcg' );

// debug($decisiondossierpcg66);
// die();
// 					$etatdossierpcg = $this->Decisiondossierpcg66->Dossierpcg66->etatDossierPcg66(
// 						$typepdo_id,
// 						$user_id,
// 						$decisionpdoId,
// 						$avistechnique,
// 						$validationavis,
// 						$retouravistechnique,
// 						$vuavistechnique/*,
// 						$etatdossierpcg*/
// 					);

			// TODO: à mettre dans le modèle -> afterDelete ?
			$success = $this->Decisiondossierpcg66->delete( $id );
			if( $success ) {
				$dernieredecision = $this->Decisiondossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Decisiondossierpcg66.dossierpcg66_id' => $dossierpcg66_id
						),
						'contain' => array(
							'Dossierpcg66'
						),
						'order' => array( 'Decisiondossierpcg66.modified DESC' )
					)
				);

							
				$typepdo_id = Set::classicExtract( $dernieredecision, 'Dossierpcg66.typepdo_id' );
				$user_id = Set::classicExtract( $dernieredecision, 'Dossierpcg66.user_id' );
				$decisionpdoId = Set::classicExtract( $dernieredecision, 'Decisiondossierpcg66.decisionpdo_id' );
				$avistechnique = Set::classicExtract( $dernieredecision, 'Decisiondossierpcg66.avistechnique' );
				$validationavis = Set::classicExtract( $dernieredecision, 'Decisiondossierpcg66.validationproposition' );
				$retouravistechnique = Set::classicExtract( $dernieredecision, 'Decisiondossierpcg66.retouravistechnique' );
				$vuavistechnique = Set::classicExtract( $dernieredecision, 'Decisiondossierpcg66.vuavistechnique' );
				
				
				$etatdossierpcg = 'instrencours';
				// Quel est l'état actuel du dossier ?
				if( empty( $dernieredecision ) ) {
					$etatdossierpcg = 'attinstr';
				}
				else {
					$etatdossierpcg = $this->Decisiondossierpcg66->Dossierpcg66->etatDossierPcg66(
						$typepdo_id,
						$user_id,
						$decisionpdoId,
						$avistechnique,
						$validationavis,
						$retouravistechnique,
						$vuavistechnique,
						$etatdossierpcg
					);
				}

				$success = $this->Decisiondossierpcg66->Dossierpcg66->updateAll(
					array( 'Dossierpcg66.etatdossierpcg' => "'{$etatdossierpcg}'" ),
					array('"Dossierpcg66"."id"' => $dossierpcg66_id )
				) && $success;
			}

			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( $this->referer() );
		}



		/**
		*   Gestion de la transmission à l'organisme payeur
		*/
		public function transmitop( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$decisiondossierpcg66 = $this->Decisiondossierpcg66->findById( $id, null, null, -1 );
			$this->set( 'decisiondossierpcg66', $decisiondossierpcg66 );
			$dossierpcg66_id = Set::classicExtract( $decisiondossierpcg66, 'Decisiondossierpcg66.dossierpcg66_id' );
			$this->set( 'dossierpcg66_id', $dossierpcg66_id );
			
			$dossierpcg66 = $this->Decisiondossierpcg66->Dossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $dossierpcg66_id
					),
					'contain' => false
				)
			);
			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );
			$this->set( 'foyer_id', $foyer_id );


			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id'] ) );
			}

			if( !empty( $this->data ) ) {
				$saved = $this->Decisiondossierpcg66->save( $this->data );
				if( $saved ) {
					$saved = $this->Decisiondossierpcg66->Dossierpcg66->updateEtatViaTransmissionop( $id ) && $saved;

					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id']) );
				}
			}
			else {
				$this->data = $decisiondossierpcg66;
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );

		}

	}
?>