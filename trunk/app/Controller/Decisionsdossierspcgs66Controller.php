<?php
	/**
	 * Code source de la classe Decisionsdossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Decisionsdossierspcgs66Controller permet de gérer les décisions
	 * d'un dossier PCG 66
	 *
	 * @package app.Controller
	 */
	class Decisionsdossierspcgs66Controller extends AppController
	{
		public $name = 'Decisionsdossierspcgs66';

		public $components = array( 'Default', 'Gedooo.Gedooo', 'Jetons2', 'Fileuploader', 'DossiersMenus' );

		public $helpers = array( 'Default2', 'Cake1xLegacy.Ajax', 'Fileuploader', 'Locale' );

		public $uses = array( 'Decisiondossierpcg66', 'Option', 'Pdf' );

		public $aucunDroit = array( 'ajaxproposition', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		public $commeDroit = array(
			'view' => 'Decisionsdossierspcgs66:index'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'ajaxproposition' => 'view',
			'avistechnique' => 'update',
			'decisionproposition' => 'update',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'filelink' => 'read',
			'fileview' => 'read',
			'index' => 'read',
			'transmitop' => 'update',
			'validation' => 'update',
			'view' => 'read',
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$options = $this->Decisiondossierpcg66->enums();
			$options = array_merge( $options, $this->Decisiondossierpcg66->Dossierpcg66->enums() );
			$options = array_merge(
				$options, $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->Decisiontraitementpcg66->enums(), $this->Decisiondossierpcg66->Dossierpcg66->Decisiondefautinsertionep66->enums(), $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->enums(), $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->enums()
			);
			$listdecisionpdo = $this->Decisiondossierpcg66->Decisionpdo->find( 'list'/* , array( 'fields' => array( 'Decisionpdo.name' ) */ );
			$typersapcg66 = $this->Decisiondossierpcg66->Typersapcg66->find( 'list' );

			$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			$compofoyerpcg66 = $this->Decisiondossierpcg66->Compofoyerpcg66->find( 'list' );

			$decisionspcgsCer = $this->Decisiondossierpcg66->Decisionpdo->find(
                'all', array(
                    'fields' => array(
                        'Decisionpdo.id',
                        'Decisionpdo.libelle',
                        'Decisionpdo.decisioncerparticulier'
                    ),
                    'conditions' => array(
                        'Decisionpdo.cerparticulier' => 'O'
                    ),
                    'contain' => false
                )
			);
			$listdecisionpcgCer = Set::combine( $decisionspcgsCer, '{n}.Decisionpdo.id', '{n}.Decisionpdo.libelle' );

			// Récupération des IDs de décisions PDO qui correspondent à une non validation du CER Particulier
			$idsDecisionNonValidCer = array( );
			foreach( $decisionspcgsCer as $decisionpcgCer ) {
				if( $decisionpcgCer['Decisionpdo']['decisioncerparticulier'] == 'N' ) {
					$idsDecisionNonValidCer[] = $decisionpcgCer['Decisionpdo']['id'];
				}
			}

			$listMotifs = $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->Motifcernonvalid66->find( 'list' );
			$this->set( compact( 'listMotifs' ) );

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
			$this->set( compact( 'options', 'listdecisionpdo', 'typersapcg66', 'compofoyerpcg66', 'forme_ci', 'listdecisionpcgCer', 'idsDecisionNonValidCer' ) );
		}

		/**
		 * http://valums.com/ajax-upload/
		 * http://doc.ubuntu-fr.org/modules_php
		 * increase post_max_size and upload_max_filesize to 10M
		 * debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		 */
		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		 * http://valums.com/ajax-upload/
		 * http://doc.ubuntu-fr.org/modules_php
		 * increase post_max_size and upload_max_filesize to 10M
		 * debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		 * FIXME: traiter les valeurs de retour
		 */
		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		 * Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
		 *
		 * @param type $id
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 * Téléchargement des fichiers préalablement associés à un traitement donné
		 *
		 * @param type $fichiermodule_id
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		 * Fonction permettant d'accéder à la page pour lier les fichiers au CER
		 *
		 * @param type $id
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Decisiondossierpcg66->dossierId( $id ) ) ) );

			$fichiers = array( );
			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisiondossierpcg66.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						),
						'Dossierpcg66'
					)
				)
			);

			$dossierpcg66_id = $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id'];
			$dossier_id = $this->Decisiondossierpcg66->Dossierpcg66->dossierId( $dossierpcg66_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $dossierpcg66_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Decisiondossierpcg66->begin();

				$saved = $this->Decisiondossierpcg66->updateAllUnBound(
					array( 'Decisiondossierpcg66.haspiecejointe' => '\''.$this->request->data['Decisiondossierpcg66']['haspiecejointe'].'\'' ),
					array(
						'"Decisiondossierpcg66"."dossierpcg66_id"' => $dossierpcg66_id,
						'"Decisiondossierpcg66"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Decisiondossierpcg66.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Decisiondossierpcg66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Decisiondossierpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			$this->_setOptions();
			$this->set( 'dossier_id', $dossier_id);
			$this->set( compact( 'personne_id', 'fichiers', 'decisiondossierpcg66' ) );
			$this->set( 'urlmenu', '/dossierspcgs66/edit/'.$dossierpcg66_id );
		}

		/**
		 * Affichage de la proposition du
		 */
		public function ajaxproposition() {
			Configure::write( 'debug', 0 );

			$decisionpcg66_id = Set::extract( $this->request->params, 'form.decisionpcg66_id' );

			$data = array(
				'defautinsertion' => Set::extract( $this->request->params, 'form.defautinsertion' ),
				'compofoyerpcg66_id' => Set::extract( $this->request->params, 'form.compofoyerpcg66_id' ),
				'recidive' => Set::extract( $this->request->params, 'form.recidive' ),
				'phase' => Set::extract( $this->request->params, 'form.phase' )
			);

			$questionspcg = array( );

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
						'list', array(
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
				$this->request->data['Decisiondossierpcg66']['decisionpcg66_id'] = $decisionpcg66_id;
			}

			$this->set( compact( 'questionspcg', 'calculpossible' ) );
			$this->render( 'ajaxproposition', 'ajax' );
		}

		/**
		 *
		 */
		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}
		}

		/**
		 *
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 *
		 */
		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 *
		 * @param integer $id
		 */
		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$dossierpcg66_id = $id;

				$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Decisiondossierpcg66->Dossierpcg66->dossierId( $id ) ) ) );

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
                            'Foyer' => array(
                                'Personne'
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
			else {
				$decisiondossierpcg66_id = $id;
				$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Decisiondossierpcg66->dossierId( $id ) ) ) );

				$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Decisiondossierpcg66.id' => $decisiondossierpcg66_id
						),
						'order' => array( 'Decisiondossierpcg66.created DESC' ),
						'contain' => array(
							'Typersapcg66',
							'Dossierpcg66' => array(
								'Contratinsertion' => array(
									'Propodecisioncer66' => array(
										'Motifcernonvalid66'
									)
								)
							)
						)
					)
				);
				$this->assert( !empty( $decisiondossierpcg66 ), 'invalidParameter' );


				$isvalidcer = Set::classicExtract( $decisiondossierpcg66, 'Propodecisioncer66.isvalidcer' );
				$this->set( compact( 'isvalidcer' ) );

				if( !empty( $isvalidcer ) && $isvalidcer == 'N' ) {
					$motifs = $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->Motifcernonvalid66Propodecisioncer66->find(
							'all', array(
						'fields' => array(
							'Motifcernonvalid66Propodecisioncer66.motifcernonvalid66_id'
						),
						'conditions' => array(
							'Motifcernonvalid66Propodecisioncer66.propodecisioncer66_id' => $decisiondossierpcg66['Propodecisioncer66']['id']
						),
						'contain' => false
							)
					);

					$motifceronvalid66 = array( );
					foreach( $motifs as $key => $value ) {
						$motifceronvalid66[] = $value['Motifcernonvalid66Propodecisioncer66']['motifcernonvalid66_id'];
					}
					$decisiondossierpcg66['Motifcernonvalid66']['Motifcernonvalid66'] = $motifceronvalid66;
				}




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
                            'Foyer' => array(
                                'Personne'
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
				$dossier_id = $this->Decisiondossierpcg66->Dossierpcg66->Foyer->dossierId( $foyer_id );
			}

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$contratinsertion_id = null;
			if( !empty( $dossierpcg66['Dossierpcg66']['contratinsertion_id'] ) ) {
				$contratinsertion_id = $dossierpcg66['Dossierpcg66']['contratinsertion_id'];
			}
			$this->set( 'contratinsertion_id', $contratinsertion_id );

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

			$this->Jetons2->get( $dossier_id );

			if( !empty( $this->request->data ) ) {
				$this->Decisiondossierpcg66->begin();
//debug( $this->request->data);
				if( $this->Decisiondossierpcg66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Decisiondossierpcg66->save( $this->request->data );
					if( !empty( $this->request->data['Decisiondossierpcg66Decisionpersonnepcg66'][0]['decisionpersonnepcg66_id'] ) ) {
						foreach( $this->request->data['Decisiondossierpcg66Decisionpersonnepcg66'] as $joinTable ) {
							if( isset( $this->request->data['Decisiondossierpcg66']['validationproposition'] ) && $this->request->data['Decisiondossierpcg66']['validationproposition'] == 'O' ) {
								$this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->Decisionpersonnepcg66->id = $joinTable['decisionpersonnepcg66_id'] && $saved;
							}
							if( $this->action == 'add' ) {
								$joinTable['decisiondossierpcg66_id'] = $this->Decisiondossierpcg66->id;
								$this->Decisiondossierpcg66->Decisiondossierpcg66Decisionpersonnepcg66->create( $joinTable );
								$saved = $this->Decisiondossierpcg66->Decisiondossierpcg66Decisionpersonnepcg66->save();
							}
						}
					}

					if( !empty( $dossierpcg66['Dossierpcg66']['contratinsertion_id'] ) ) {
						// Proposition de non validation
						if( $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
							$saved = $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->save( $this->request->data );

							if( !isset( $this->request->data['Motifcernonvalid66'] ) && !empty( $propodecisioncer66 ) ) {
								$saved = $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->Motifcernonvalid66Propodecisioncer66->deleteAll(
												array(
													'Motifcernonvalid66Propodecisioncer66.propodecisioncer66_id' => $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->id
												)
										) && $saved;

								$saved = $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->updateAllUnBound(
												array(
											'Propodecisioncer66.motifficheliaison' => null,
											'Propodecisioncer66.motifnotifnonvalid' => null
												), array(
											'Propodecisioncer66.id' => $this->Decisiondossierpcg66->Dossierpcg66->Contratinsertion->Propodecisioncer66->id
												)
										) && $saved;
							}
						}
					}
					//


					if( $saved ) {
						$saved = $this->Decisiondossierpcg66->Dossierpcg66->updateEtatViaDecisionFoyer( $this->Decisiondossierpcg66->id ) && $saved;
					}

                    
                    // Clôture des traitements PCGs non clôturés, appartenant même à un autre dossier 
                    // que celui auquel je suis lié
                    if( $saved && !empty( $this->request->data['Traitementpcg66']['Traitementpcg66'] ) ) {
//                        $saved = $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->updateAllUnBound( 
//                            array( 'Traitementpcg66.clos' => '\'O\'' ),
//                            array(
//                                'Traitementpcg66.id IN' => $this->request->data['Traitementpcg66']['traitementnonclosdecision']
//                            )
//                        ) && $saved;
                        
                         $saved = $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->Traitementpcg66->updateAllUnBound( 
                            array( 'Traitementpcg66.clos' => '\'O\'' ),
                            array(
                                'Traitementpcg66.id IN' => $this->request->data['Traitementpcg66']['Traitementpcg66']
                            )
                        ) && $saved;
                    }

					if( $saved ) {
						$this->Decisiondossierpcg66->commit();
						$this->Jetons2->release( $dossier_id );
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
			else if( $this->action != 'add' ) {
				$this->request->data = $decisiondossierpcg66;

				// Récupération des types de RSA sélectionnés
				$typesrsapcg = $this->Decisiondossierpcg66->Decisiondossierpcg66Typersapcg66->find(
					'list',
					array(
						'fields' => array( "Decisiondossierpcg66Typersapcg66.id", "Decisiondossierpcg66Typersapcg66.typersapcg66_id" ),
						'conditions' => array(
							"Decisiondossierpcg66Typersapcg66.decisiondossierpcg66_id" => $decisiondossierpcg66_id
						)
					)
				);
				$this->request->data['Typersapcg66']['Typersapcg66'] = $typesrsapcg;
			}

            //Liste des personne sliées au traitement
			$personnespcgs66 = $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->find(
					'all', array(
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
			$listeFicheAReporter = array( );
			foreach( $personnespcgs66 as $i => $personnepcg66 ) {
				if( !empty( $personnepcg66['Traitementpcg66'] ) ) {
					foreach( $personnepcg66['Traitementpcg66'] as $traitementpcg66 ) {
						if( $traitementpcg66['reversedo'] == 1 ) {
							$listeFicheAReporter[] = $traitementpcg66;
						}
					}
				}
			}
			$this->set( compact( 'listeFicheAReporter' ) );
            
            
//            debug($dossierpcg66    );
            //Liste des traitements non clos appartenant aux dossiers liés à mon Foyer
            $listeTraitementsNonClos = array();

            $personnesFoyerIds = Hash::extract( $dossierpcg66, 'Foyer.Personne.{n}.id' );
            $listeTraitementsNonClos = $this->Decisiondossierpcg66->Dossierpcg66->Personnepcg66->listeTraitementpcg66NonClos( array_values( $personnesFoyerIds ), $this->action, $this->request->data );

            $this->set( 'listeTraitementsNonClos', $listeTraitementsNonClos );

			// avistechniquemodifiable, validationmodifiable
			$avistechniquemodifiable = $validationmodifiable = false;
			switch( $dossierpcg66['Dossierpcg66']['etatdossierpcg'] ) {
				case 'attavistech':
					$avistechniquemodifiable = ( $this->action != 'add' );
					break;
				case 'attval':
				case 'decisionvalid':
                    $avistechniquemodifiable = ( $this->action != 'add' );
					$validationmodifiable = ( $this->action != 'add' );
					break;
				case 'decisionnonvalid':
                    $avistechniquemodifiable = ( $this->action != 'add' );
					$validationmodifiable = ( $this->action != 'add' );
					break;
				case 'decisionnonvalidretouravis':
                    $avistechniquemodifiable = ( $this->action != 'add' );
					$validationmodifiable = ( $this->action != 'add' );
					break;
				case 'decisionvalidretouravis':
                    $avistechniquemodifiable = ( $this->action != 'add' );
					$validationmodifiable = ( $this->action != 'add' );
					break;
				case 'attpj':
				case 'atttransmisop':
					$avistechniquemodifiable = ( $this->action != 'add' );
					$validationmodifiable = ( $this->action != 'add' );
					break;
                case 'transmisop':
					$avistechniquemodifiable = ( $this->action != 'add' );
					$validationmodifiable = ( $this->action != 'add' );
					break;
			}

			$this->set( compact( 'personnespcgs66', 'dossierpcg66', 'decisiondossierpcg66', 'avistechniquemodifiable', 'validationmodifiable' ) );

			$this->_setOptions();

			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
			$this->render( 'add_edit' );
		}

		/**
		 * Enregistrement du courrier de proposition lors de l'enregistrement de la proposition
		 *
		 * @param integer $id
		 */
		public function decisionproposition( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$this->DossiersMenus->checkDossierMenu( array( 'id' => $this->Decisiondossierpcg66->dossierId( $id ) ) );

			$pdf = $this->Decisiondossierpcg66->getPdfDecision( $id );

			if( $pdf ) {
				$success = true;

				if( isset( $this->request->params['named']['save'] ) && $this->request->params['named']['save'] ) {
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
					$this->Gedooo->sendPdfContentToClient( $pdf, 'Décision.pdf' );
				}
			}

			$this->Session->setFlash( 'Impossible de générer la décision', 'default', array( 'class' => 'error' ) );
			$this->redirect( $this->referer() );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function view( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Decisiondossierpcg66->dossierId( $id ) ) ) );


			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisiondossierpcg66.id' => $id
					),
					'contain' => array(
						'Decisionpdo',
						'Dossierpcg66' => array(
							'Personnepcg66',
							'Foyer'
						),
						'Fichiermodule',
                        'Orgtransmisdossierpcg66'
					)
				)
			);

			$this->assert( !empty( $decisiondossierpcg66 ), 'invalidParameter' );

			$this->set( 'dossier_id', $this->Decisiondossierpcg66->dossierId( $id ) );

			// Retour à la page d'édition de la PDO
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', Set::classicExtract( $decisiondossierpcg66, 'Decisiondossierpcg66.dossierpcg66_id' ) ) );
			}
            
             // Liste des organismes auxquels on transmet le dossier
            if( !empty( $decisiondossierpcg66['Orgtransmisdossierpcg66'] ) ) {
                $listOrgs = Hash::extract( $decisiondossierpcg66, 'Orgtransmisdossierpcg66.{n}.name' );
                $orgs = implode( ', ',  $listOrgs );
            }

			$options = $this->Decisiondossierpcg66->enums();
			$this->set( compact( 'decisiondossierpcg66', 'orgs' ) );
			$this->_setOptions();

			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$decisiondossierpcg66['Dossierpcg66']['foyer_id'] );
		}

		/**
		 * Suppression de la proposition de décision
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'id' => $this->Decisiondossierpcg66->dossierId( $id ) ) );

			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
					'first', array(
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

			$success = $this->Decisiondossierpcg66->delete( $id );
			if( $success ) {
				$dernieredecision = $this->Decisiondossierpcg66->find(
						'first', array(
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
							$typepdo_id, $user_id, $decisionpdoId, $avistechnique, $validationavis, $retouravistechnique, $vuavistechnique, $etatdossierpcg
					);
				}

				$success = $this->Decisiondossierpcg66->Dossierpcg66->updateAllUnBound(
								array( 'Dossierpcg66.etatdossierpcg' => "'{$etatdossierpcg}'" ), array( '"Dossierpcg66"."id"' => $dossierpcg66_id )
						) && $success;
			}

			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( $this->referer() );
		}

		/**
		 * Gestion de la transmission à l'organisme payeur
		 *
		 * @param integer $id
		 */
		public function transmitop( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Decisiondossierpcg66->dossierId( $id ) ) ) );

			$qd_decisiondossierpcg66 = array(
				'conditions' => array(
					'Decisiondossierpcg66.id' => $id
				),
				'contain' => array(
					'Orgtransmisdossierpcg66'
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find( 'first', $qd_decisiondossierpcg66 );
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
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id'] ) );
			}

			if( !empty( $this->request->data ) ) {
// 			debug($this->request->data);
				$this->Decisiondossierpcg66->begin();
				$saved = $this->Decisiondossierpcg66->save( $this->request->data );
				if( $saved ) {
					
					$saved = $this->Decisiondossierpcg66->Dossierpcg66->updateEtatViaTransmissionop( $id ) && $saved;

					$this->Decisiondossierpcg66->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id'] ) );
				}
				else {
					$this->Decisiondossierpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $decisiondossierpcg66;

				// Récupération des types de RSA sélectionnés
				$orgstransmisdossierspcgs66 = $this->Decisiondossierpcg66->Decdospcg66Orgdospcg66->find(
					'list',
					array(
						'fields' => array(
							"Decdospcg66Orgdospcg66.id",
							"Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id"
						),
						'conditions' => array(
                            "Decdospcg66Orgdospcg66.decisiondossierpcg66_id" => $id
						)
					)
				);
// 				
				$this->request->data['Orgtransmisdossierpcg66']['Orgtransmisdossierpcg66'] = $orgstransmisdossierspcgs66;
			}

            // Liste des Ids d'organisme enregistrés en lien avec la décision avant la désactivation de cet organisme
            $orgsIds = Hash::extract( $this->request->data, 'Orgtransmisdossierpcg66.Orgtransmisdossierpcg66' );

            $conditions = array(
                'Orgtransmisdossierpcg66.isactif' => '1'
            );
            if( !empty( $orgsIds ) ) {
                $conditions = array(
                    'OR' => array(
                        $conditions,
                       array(
                           'Orgtransmisdossierpcg66.id' => $orgsIds
                       )
                    )
                );
            }

            $listeOrgstransmisdossierspcgs66 = $this->Decisiondossierpcg66->Orgtransmisdossierpcg66->find(
                'list',
                array(
                    'conditions' => $conditions,
                    'order' => array( 'Orgtransmisdossierpcg66.name ASC' )
                )
            );

			$this->_setOptions();
            $this->set( compact( 'listeOrgstransmisdossierspcgs66' ) );
			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
		}

		/**
		 * Affiche le formulaire d'ajout/modification selon l'état du dossier
		 * et selon le profil de l'utilisateur (avis technique)
		 * @param integer $id ID d'une décision liée au dossier PCG
		 *
		 */
		public function avistechnique( $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Affiche le formulaire d'ajout/modification selon l'état du dossier
		 * et selon le profil de l'utilisateur (validation après avis technique)
		 * @param integer $id ID d'une décision liée au dossier PCG
		 */
		public function validation( $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}
		
		
		/**
		 *
		 * @param integer $id
		 */
		public function cancel( $id ) {
			$qd_decisiondossierpcg66 = array(
				'conditions' => array(
					'Decisiondossierpcg66.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find( 'first', $qd_decisiondossierpcg66 );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Decisiondossierpcg66->dossierId( $id ) ) ) );

			$dossier_id = $this->Decisiondossierpcg66->dossierId( $id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id'] ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Decisiondossierpcg66->begin();

				$saved = $this->Decisiondossierpcg66->save( $this->request->data );
				$saved = $this->Decisiondossierpcg66->updateAllUnBound(
					array( 'Decisiondossierpcg66.etatdossierpcg' => '\'annule\'' ),
					array(
						'"Decisiondossierpcg66"."dossierpcg66_id"' => $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id'],
						'"Decisiondossierpcg66"."id"' => $decisiondossierpcg66['Decisiondossierpcg66']['id']
					)
				) && $saved;

				if( $saved ) {
					$this->Decisiondossierpcg66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $decisiondossierpcg66['Decisiondossierpcg66']['dossierpcg66_id'] ) );
				}
				else {
					$this->Decisiondossierpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/erreur' );
				}
			}
			else {
				$this->request->data = $decisiondossierpcg66;
			}
		}
	}
?>