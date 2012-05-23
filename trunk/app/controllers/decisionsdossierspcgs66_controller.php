<?php
	class Decisionsdossierspcgs66Controller extends AppController
	{
		public $name = 'Decisionsdossierspcgs66';
		/**
		* @access public
		*/

		public $components = array( 'Default', 'Gedooo.Gedooo' );

		public $helpers = array( 'Default2', 'Ajax', 'Fileuploader', 'Locale' );
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
				$this->Decisiondossierpcg66->Dossierpcg66->Decisiondefautinsertionep66->enums(),
				$this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->enums(),
				$this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->enums()
			);
			$listdecisionpdo = $this->Decisiondossierpcg66->Decisionpdo->find( 'list'/*, array( 'fields' => array( 'Decisionpdo.name' )*/ );
			$typersapcg66 = $this->Decisiondossierpcg66->Typersapcg66->find( 'list' );

			$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			$compofoyerpcg66 = $this->Decisiondossierpcg66->Compofoyerpcg66->find( 'list' );
			
			$listdecisionpcgCer = $this->Decisiondossierpcg66->Decisionpdo->find(
				'list',
				array(
					'conditions' => array(
						'Decisionpdo.cerparticulier' => 'O'
					)
				)
			);

			$this->set( compact( 'options', 'listdecisionpdo', 'typersapcg66', 'compofoyerpcg66', 'forme_ci', 'listdecisionpcgCer' ) );
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
							'Fichiermodule',
							'Contratinsertion'
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
							'Fichiermodule',
							'Contratinsertion'
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
			
			//Liste des traitements avec une fiche de calcul devant être reporter dans la décision
			$listeFicheAReporter = array();
			foreach( $personnespcgs66 as $i => $personnepcg66 ) {
				if( !empty( $personnepcg66['Traitementpcg66'] ) ) {
					foreach( $personnepcg66['Traitementpcg66'] as $traitementpcg66 ){
						if( $traitementpcg66['reversedo'] == 1 ){
							$listeFicheAReporter[] = $traitementpcg66;
						}
					}
				}
			}
			$this->set( compact( 'listeFicheAReporter' ) );

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