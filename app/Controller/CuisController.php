<?php
	/**
	 * Code source de la classe CuisController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CuisController permet de gérer les CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Controller
	 */
	class CuisController extends AppController
	{
		public $name = 'Cuis';

		public $uses = array( 'Cui', 'Option', 'Departement' );

		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Xform', 'Fileuploader', 'Cake1xLegacy.Ajax' );

		public $components = array(
			'Default',
			'Fileuploader',
			'Gedooo.Gedooo',
			'Jetons2',
			'DossiersMenus'
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
			'cancel' => 'update',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'filelink' => 'read',
			'fileview' => 'read',
			'impression' => 'read',
			'index' => 'read',
			'indexparams' => 'read',
			'valider' => 'update',
			'view' => 'read',
			'synthesecui66' => 'read',
		);

		public $aucunDroit = array( 'ajaxtaux','ajaxfileupload', 'impression', 'fileview', 'download', 'ajaxemployeur' );

		public $commeDroit = array(
			'add' => 'Cuis:edit',
			'view' => 'Cuis:index'
		);

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {
			$options = array();
			$options = $this->Cui->enums();
			$optionsaccompagnement = $this->Cui->Accompagnementcui66->enums();
			$options = Set::merge( $options, $optionsaccompagnement );

			$secteursactivites = $this->Cui->Personne->Dsp->Libsecactderact66Secteur->find(
				'list',
				array(
					'contain' => false,
					'order' => array( 'Libsecactderact66Secteur.code' )
				)
			);
			$this->set( 'secteursactivites', $secteursactivites );

			$codesromemetiersdsps66 = $this->Cui->Personne->Dsp->Libderact66Metier->find(
				'all',
				array(
					'contain' => false,
					'order' => array( 'Libderact66Metier.code' )
				)
			);
			foreach( $codesromemetiersdsps66 as $coderomemetierdsp66 ) {
				$options['Coderomemetierdsp66'][$coderomemetierdsp66['Libderact66Metier']['coderomesecteurdsp66_id'].'_'.$coderomemetierdsp66['Libderact66Metier']['id']] = $coderomemetierdsp66['Libderact66Metier']['code'].'. '.$coderomemetierdsp66['Libderact66Metier']['name'];
			}

			$typevoie = $this->Option->typevoie();
			$dureeprisecharge = array_range( '3', '36' );
			$options = Hash::insert( $options, 'typevoie', $typevoie );
			$options = Hash::insert( $options, 'dureeprisecharge', $dureeprisecharge );

			$secteurscuis = $this->Cui->Secteurcui->find(
				'list',
				array(
					'contain' => false,
					'order' => array( 'Secteurcui.name' )
				)
			);
			$this->set( 'secteurscuis', $secteurscuis );

			$typevoie = $this->Option->typevoie();
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );

			$dept = $this->Departement->find( 'list', array( 'fields' => array( 'numdep', 'name' ), 'contain' => false ) );
			$this->set( compact( 'dept' ) );



			if( Configure::read( 'CG.cantons' ) ) {
				$Canton = ClassRegistry::init( 'Canton' );
				$this->set( 'cantons', $Canton->selectList() );
			}


			$this->set( 'prestataires', $this->Cui->Referent->listOptions() );
			$this->set( 'referents', $this->Cui->Referent->find( 'list', array( 'recursive' => false ) ) );

			$this->set( 'structs', $this->Cui->Structurereferente->listeParType( array( 'cui' => true ) ) );


			$this->set( 'rsaSocle', $this->Option->natpf() );

			$options[$this->modelClass]['raisonsocialepartenairecui66_id'] = $this->Cui->Partenaire->Raisonsocialepartenairecui66->find(
				'list',
				array(
					'contain' => false,
					'order' => array( 'Raisonsocialepartenairecui66.name DESC' )
				)
			);

			$options = array_merge(
				array(
					'Cui' => array(
						'isinscritpe' => array( '0' => 'Non', '1' => 'Oui' )
					)
				),
				$options
			);
			$this->set( compact( 'options' ) );
		}


		/**
		 * Ajax pour les partenaires fournissant les actions
		 *
		 * @param integer $secteurcui_id
		 */

		public function ajaxemployeur( $partenaire_id = null, $actioncandidat_id = null ) {
			Configure::write( 'debug', 0 );

			$dataPartenaire_id = Set::extract( $this->request->data, 'Cui.partenaire_id' );
			$partenaire_id = ( empty( $partenaire_id ) && !empty( $dataPartenaire_id ) ? $dataPartenaire_id : $partenaire_id );

			$partenaire = null;
			if( !is_null( $partenaire_id ) && !empty( $partenaire_id ) && ( $partenaire_id != '_' ) ) {
				$partenaire = $this->Cui->Partenaire->find(
					'first',
					array(
						'conditions' => array(
							'Partenaire.id' => $partenaire_id
						),
						'contain' => array(
							'Contactpartenaire' => array(
								'Actioncandidat' => array(
									'Fichiermodule'
								)
							)
						)
					)
				);


				$dataActioncandidat_id = suffix( Set::extract( $this->request->data, 'Cui.actioncandidat_id' ) );
				$actioncandidat_id = ( empty( $actioncandidat_id ) && !empty( $dataActioncandidat_id ) ? $dataActioncandidat_id : $actioncandidat_id );

				$actioncandidat = null;
				if( !is_null( $actioncandidat_id ) && !empty( $actioncandidat_id ) && ( $actioncandidat_id != '_' ) ) {

					$actioncandidat = $this->Cui->Actioncandidat->find(
						'first',
						array(
							'conditions' => array(
								'Actioncandidat.id' => $actioncandidat_id
							),
							'contain' => array(
								'Fichiermodule' => array(
									'fields' => array(
										'Fichiermodule.id',
										'Fichiermodule.name',
										'Fichiermodule.created'
									)
								)
							)
						)
					);

					$referent = null;
					if( ($actioncandidat['Actioncandidat']['correspondantaction'] == 1) && !empty( $actioncandidat['Actioncandidat']['referent_id'] ) ) {
						$this->Cui->Personne->Referent->recursive = -1;
						$referent = $this->Cui->Personne->Referent->read( null, $actioncandidat['Actioncandidat']['referent_id'] );
					}
					$this->set( compact( 'referent' ) );
				}
			}
			$this->_setOptions();
			$this->set( compact( 'partenaire', 'actioncandidat' ) );
			$this->render( 'ajaxemployeur', 'ajax' );
		}

		/**
		 * Paramétrages des CUIs.
		 *
		 * @return void
		 */
		public function indexparams() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
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
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 *   Téléchargement des fichiers préalablement associés à un traitement donné
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		 * Fonction permettant d'accéder à la page pour lier les fichiers au CER
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Cui->personneId( $id ) ) ) );

			$fichiers = array( );
			$cui = $this->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $cui['Cui']['personne_id'];
			$dossier_id = $this->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
                $this->Cui->begin();
				$saved = $this->Cui->updateAllUnBound(
					array( 'Cui.haspiecejointe' => '\''.$this->request->data['Cui']['haspiecejointe'].'\'' ),
					array(
						'"Cui"."personne_id"' => $personne_id,
						'"Cui"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Cui.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Cui->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Cui->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'cui' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
		}

		/**
		 * Liste des CUIs appartenant à un allocataire donné.
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$this->_setEntriesAncienDossier( $personne_id, 'Cui' );

			// Précondition: La personne est-elle bien en Rsa Socle ?
			$alerteRsaSocle = $this->Cui->_prepare( $personne_id );
			$this->set( 'alerteRsaSocle', $alerteRsaSocle );

            // Alerte à afficher si le titre de séjour se termine bientôt
            $alerteTitreSejour = $this->Cui->Personne->nbMoisAvantFinTitreSejour($personne_id);
            $this->set( 'alerteTitreSejour', $alerteTitreSejour );

			$cuis = $this->Cui->find(
				'all',
				array(
					'fields' => array_merge(
						$this->Cui->fields(),
						$this->Cui->Secteurcui->fields(),
						$this->Cui->Partenaire->fields(),
						array(
                            'Decisioncui66.id',
							$this->Cui->Fichiermodule->sqNbFichiersLies( $this->Cui, 'nb_fichiers_lies' ),
                            $this->Cui->Propodecisioncui66->sqNbPropositions( $this->Cui, 'nb_proposition' )
						)
					),
					'conditions' => array(
						'Cui.personne_id' => $personne_id
					),
                    'recursive' => -1,
					'joins' => array(
						$this->Cui->join( 'Secteurcui' ),
						$this->Cui->join( 'Partenaire' ),
                        $this->Cui->join( 'Decisioncui66', array( 'type' => 'LEFT OUTER' ) )
					),
                    'order' => array( 'Cui.datecontrat DESC', 'Cui.id DESC' )
				)
			);

			foreach( $cuis as $i => $cui ) {
				if( !empty( $cui['Partenaire']['libstruc'] ) ) {
					$cuis[$i]['Cui']['nomemployeur'] = $cui['Partenaire']['libstruc'];
				}

                if( !is_null( $cui['Decisioncui66']['id'] ) ) {
                    $action = 'decisioncui';
                }
                else {
                    $action = 'add';
                }
                $cuis[$i]['Cui']['action'] = $action;

			}

            // Est-ce qu'un référent est lié à l'allocataire ?
            $persreferent = $this->Cui->Personne->PersonneReferent->find(
                'count',
                array(
                    'conditions' => array(
                        'PersonneReferent.personne_id' => $personne_id,
                        'PersonneReferent.dfdesignation IS NULL'
                    ),
                    'recursive' => -1
                )
            );
            $this->set( compact( 'persreferent' ) );

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'cuis' ) );

			$this->render( 'index_cg'.Configure::read( 'Cg.departement' ) );
		}

		/**
		 * Formulaire d'ajout d'un CUI pour un allocataire donné.
		 *
		 * @param integer $personne_id
		 */
		public function add( $personne_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un CUI donné.
		 *
		 * @param integer $id
		 */
		public function edit( $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


        /**
		 * Traitement du formulaire d'ajout ou de modification de CUI.
		 *
		 * @param inetger $id Correspond à l'id de la Personne en cas d'ajout, à l'id du Cui en cas de modification.
		 */
		protected function _add_edit( $id = null ) {

            if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			else {
				$this->Cui->id = $id;
				$personne_id = $this->Cui->field( 'personne_id' );
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$dossier_id = $this->Cui->Personne->dossierId( $personne_id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			$this->set( 'dossier_id', $dossier_id );

			// ------------------------------------------------------------------------------------------
			// Affichage des valeurs non marchnades si le secteur choisi est de type non marchand
			$valeursSecteurcui = $this->Cui->Secteurcui->find(
				'all',
				array(
					'order' => array( 'Secteurcui.isnonmarchand DESC', 'Secteurcui.name ASC' )
				)
			);
			$secteur_isnonmarchand_id = Hash::extract( $valeursSecteurcui, '{n}.Secteurcui[isnonmarchand=1].id' );

			$secteurscuisForm = array(
				'Secteurcui' => array(
					'id' => Set::combine( $valeursSecteurcui, '{n}.Secteurcui.id', '{n}.Secteurcui.name' )
				)
			);
			$this->set( compact( 'secteur_isnonmarchand_id', 'secteurscuisForm' ) );
			// ------------------------------------------------------------------------------------------
			 //On affiche les actions inactives en édition mais pas en ajout,
            // afin de pouvoir gérer les actions n'étant plus prises en compte mais toujours en cours
            $isactive = 'O';
            if( $this->action == 'edit' ){
                $isactive = array( 'O', 'N' );
            }
            $employeursCui = $this->{$this->modelClass}->Actioncandidat->Partenaire->find(
				'list',
				array(
					'conditions' => array(
						'Partenaire.iscui' => '1'
					),
					'order' => array( 'Partenaire.libstruc ASC' )
				)
			);

			$actionsCui = $this->{$this->modelClass}->Actioncandidat->find(
				'all',
				array(
					'fields' => array(
						'Actioncandidat.id',
						'Actioncandidat.name',
						'Actioncandidat.contactpartenaire_id',
						'Partenaire.id',
						'Partenaire.libstruc',
						'Contactpartenaire.partenaire_id',
						'( "Partenaire"."id" || \'_\'|| "Actioncandidat"."id" ) AS "Actioncandidat__id"',
						'Actioncandidat.name',
					),
					'joins' => array(
						$this->Cui->Actioncandidat->join( 'Contactpartenaire', array( 'type' => 'INNER' ) ),
						$this->Cui->Actioncandidat->Contactpartenaire->join( 'Partenaire', array( 'type' => 'INNER' ) )
					),
					'order' => array( 'Actioncandidat.name ASC' )
				)
			);

			$valeursactionsparpartenaires = array();
			foreach( $actionsCui as $action ) {
				$valeursactionsparpartenaires[$action['Actioncandidat']['id']] = $action['Actioncandidat']['name'];
			}
			$this->set( compact( 'valeursactionsparpartenaires', 'employeursCui' ) );
			// ------------------------------------------------------------------------------------------


			$taux_cgs_cuis = $this->Cui->Secteurcui->Tauxcgcui->find( 'all' );
			$this->set( compact( 'taux_cgs_cuis' ) );

//			$this->set( 'personne', $personne );

			/// Calcul du numéro du contrat d'insertion
			$nbCui = $this->Cui->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );


			if( !empty( $this->request->data ) ) {
                $success = true;

                $data = $this->request->data;
                $this->{$this->modelClass}->begin();

				if( $this->action == 'add' ) {
					$data['Cui']['rangcui'] = $nbCui + 1;
				}

				$dataCui = $data['Cui'];
				//Sauvegarde (création d'un nouveau partenaire si nouvel employeur
				if( !empty( $data['Cui']['newemployeur'] ) && ( $data['Cui']['newemployeur'] == '1' ) && !empty( $data['Partenaire'] ) ) {
					$partenaire = Hash::filter( (array)$data['Partenaire'] );
					if( !empty( $partenaire ) ) {
						$this->Cui->Partenaire->create( $data );
						$success = $this->Cui->Partenaire->save();
						$dataCui['partenaire_id'] = $this->{$this->modelClass}->Partenaire->id;
					}
				}
                
				$this->{$this->modelClass}->create( $data );
				$success = $this->{$this->modelClass}->save() && $success;
                
                

				// Nettoyage des Periodes d'immersion
				$keys = array_keys( $this->Cui->Accompagnementcui66->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );
				unset( $defaults['cui_id'] );

				//Sauvegarde des accompagnements cuis si présents dans la requête
				if( !empty( $data['Accompagnementcui66'] ) ) {
					$data['Accompagnementcui66'] = Set::merge( $defaults, $data['Accompagnementcui66'] );
				}

				if( !empty( $data['Cui']['isaci'] ) /*&& ( $data['Cui']['iscae'] == '1' ) */ && !empty( $data['Accompagnementcui66'] ) ) {
					$Accompagnementcui66 = Hash::filter( (array)$data['Accompagnementcui66'] );
					if( !empty( $Accompagnementcui66 ) ) {
						$this->{$this->modelClass}->Accompagnementcui66->create( $data );
						if( $this->action == 'add' ) {
							$this->{$this->modelClass}->Accompagnementcui66->set( 'cui_id', $this->{$this->modelClass}->getLastInsertID() );
						}
						else if( $this->action == 'edit' ) {
							$this->{$this->modelClass}->Accompagnementcui66->set( 'cui_id', Set::classicExtract( $data, 'Cui.id' ) );
						}
						$success = $this->{$this->modelClass}->Accompagnementcui66->save() && $success;
					}
				}
                
                
				if( $success ) {
                    if( $this->action == 'edit') {
                        $positioncui66 = $this->Cui->calculPosition($data);
                        $success = $this->Cui->updateAllUnBound(
                            array( 'Cui.positioncui66' => '\''.$positioncui66.'\'' ),
                            array(
                                '"Cui"."personne_id"' => $personne_id,
                                '"Cui"."id"' => $data['Cui']['id']
                            )
                        ) && $success;
                    }
                    
					$this->{$this->modelClass}->commit(); //FIXME
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

            // Mail envoyé à l'employeur ?
            $isDossierComplet = false;
            $isRecu = ''; //FIXME
            $dataCaf = $this->Cui->dataCafAllocataire( $personne_id );
            if( empty( $this->request->data ) ) {
				$this->request->data = $this->Cui->prepareFormDataAddEdit( $personne_id, ( ( $this->action == 'add' ) ? null : $id ), $this->Session->read( 'Auth.User.id' ) );
//    debug($this->request->data);
                if( $this->action == 'edit' && $this->request->data['Cui']['sendmailemployeur'] == '1' ) {
                    $isDossierComplet = $this->Cui->isDossierComplet($this->request->data);
                    $isRecu = $this->request->data['Cui']['dossierrecu'];
                    
                }
                
			}

            // Liste des pièces liées au mail, paramétrées dans l'application
            $piecesmailscuis66 = $this->Cui->Piecemailcui66->find( 'list');
            $this->set( 'piecesmailscuis66', $piecesmailscuis66);
            
            // Liste des modèles de mail pour les employeurs paramétrés  dans l'application
            $textsmailscuis66 = $this->Cui->Textmailcui66->find('list');
            $this->set( 'textsmailscuis66', $textsmailscuis66);
            

            
			$this->_setOptions();
			$this->set( 'nbCui', $nbCui );
            $this->set( 'isDossierComplet', $isDossierComplet );
            $this->set( 'isRecu', $isRecu );
			$this->set( 'personne_id', $personne_id );
            $this->set( 'dataCaf', $dataCaf );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			if( Configure::read( 'Cg.departement') == '66') { //FIXME
                $this->render( 'add_edit_cg66' );
            }
            else {
                $this->render( 'add_edit' );
            }
		}



		/**
		 * Formulaire de validation d'un CUI.
		 *
		 * @param integer $cui_id
		 */
		public function valider( $cui_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Cui->personneId( $cui_id ) ) ) );

			$qd_cui = array(
				'conditions' => array(
					'Cui.id' => $cui_id
				)
			);
			$cui = $this->Cui->find( 'first', $qd_cui );
			$this->assert( !empty( $cui ), 'invalidParameter' );

			$this->set( 'personne_id', $cui['Cui']['personne_id'] );

			$dossier_id = $this->Cui->dossierId( $cui_id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $cui['Cui']['personne_id'] ) );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->Cui->saveAll( $this->request->data ) ) {
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $cui['Cui']['personne_id'] ) );
				}
			}
			else {
				$this->request->data = $cui;
			}
			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$cui['Cui']['personne_id'] );
		}

		/**
		 * Imprime un CUI.
		 *
		 * @param integer $id L'id du CUI que l'on veut imprimer
		 * @return void
		 */
		public function impression( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Cui->personneId( $id ) ) );

			$pdf = $this->Cui->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'cui_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier du contrat unique d\'insertion.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Tentative de suppression d'un CUI.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Cui->personneId( $id ) ) );

			$this->Default->delete( $id );
		}

		/**
		 * Visualisation d'un CUI.
		 *
		 * @param integer $id
		 */
		public function view( $id ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Cui->personneId( $id ) ) ) );

			$qd_cui = array(
				'conditions' => array(
					$this->modelClass.'.id' => $id
				),
				'fields' => null,
				'order' => null,
//				'recursive' => -1,
                'contain' => array(
                    'Rupturecui66' => array(
                        'Motifrupturecui66'
                    ),
                    'Secteurcui'
                )
			);
			$cui = $this->{$this->modelClass}->find( 'first', $qd_cui );
			$personne = $this->{$this->modelClass}->Personne->detailsApre( $cui['Cui']['personne_id'], $this->Session->read( 'Auth.User.id' ) );
			$this->set( compact( 'personne', 'cui' ) );

			$this->set( 'organismes', $this->Cui->Structurereferente->listeParType( array() ) );

            // Liste des employeurs et liste des actions
            $employeursCui = $this->{$this->modelClass}->Actioncandidat->Partenaire->find(
                'list',
                array(
                    'conditions' => array(
                        'Partenaire.iscui' => '1'
                    ),
                    'order' => array( 'Partenaire.libstruc ASC' )
                )
            );

            $actionsCui = $this->{$this->modelClass}->Actioncandidat->find(
                'all',
                array(
                    'fields' => array(
                        'Actioncandidat.id',
                        'Actioncandidat.name',
                        'Actioncandidat.contactpartenaire_id',
                        'Partenaire.id',
                        'Partenaire.libstruc',
                        'Contactpartenaire.partenaire_id',
                        '( "Partenaire"."id" || \'_\'|| "Actioncandidat"."id" ) AS "Actioncandidat__id"',
                        'Actioncandidat.name',
                    ),
                    'joins' => array(
                        $this->Cui->Actioncandidat->join( 'Contactpartenaire', array( 'type' => 'INNER' ) ),
                        $this->Cui->Actioncandidat->Contactpartenaire->join( 'Partenaire', array( 'type' => 'INNER' ) )
                    ),
                    'order' => array( 'Actioncandidat.name ASC' )
                )
            );

            $valeursactionsparpartenaires = array();
            foreach( $actionsCui as $action ) {
                $valeursactionsparpartenaires[$action['Actioncandidat']['id']] = $action['Actioncandidat']['name'];
            }
            $this->set( compact( 'valeursactionsparpartenaires', 'employeursCui' ) );


			$this->_setOptions();
		}

        /**
         * Annulation du CUI
         *
         * @param integer $id
         */
        public function cancel( $id ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Cui->personneId( $id ) ) ) );

			$qd_cui = array(
				'conditions' => array(
					$this->modelClass.'.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$cui = $this->{$this->modelClass}->find( 'first', $qd_cui );

			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
			$this->set( 'personne_id', $personne_id );

			$dossier_id = $this->Cui->dossierId( $id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Cui->begin();

				$saved = $this->Cui->save( $this->request->data );
				$saved = $this->{$this->modelClass}->updateAllUnBound(
					array(
                        'Cui.positioncui66' => '\'annule\'',
                        'Cui.decisioncui' => '\'annule\''
                    ),
					array(
						'"Cui"."personne_id"' => $cui['Cui']['personne_id'],
						'"Cui"."id"' => $cui['Cui']['id']
					)
				) && $saved;

				if( $saved ) {
					$this->Cui->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cui->rollback();
//                    debug( $this->Cui->validationErrors);
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
				}
			}
			else {
				$this->request->data = $cui;
			}
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
		}
        
        
        
        /**
		 * Permet d'envoyer un mail à l'employeur en lien avec le CUI
		 *
		 * @param integer $id
		 */
		public function maillink( $id = null ) {
			$personne_id = $this->Cui->personneId( $id );
            $this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$sqDernierReferent = $this->Cui->Personne->PersonneReferent->sqDerniere( 'Personne.id' );

            $dossier_id = $this->Cui->dossierId( $id );
			$this->Jetons2->get( $dossier_id );
            
            $cui = $this->Cui->find(
                'first',
                array(
                    'fields' => array_merge(
                        $this->Cui->fields(),
                        $this->Cui->Personne->fields(),
                        $this->Cui->Partenaire->fields(),
                        $this->Cui->Textmailcui66->fields()
                    ),
                    'conditions' => array(
                        'Cui.id' => $id
                    ),
                    'joins' => array(
                        $this->Cui->join( 'Personne', array( 'type' => 'INNER' ) ),
                        $this->Cui->join( 'Partenaire', array( 'type' => 'INNER') ),
                        $this->Cui->join( 'Textmailcui66', array( 'type' => 'INNER') )
                    ),
                    'contain' => false
                )
            );
            
            $user = $this->Cui->User->find(
                'first',
                 array(
                     'conditions' => array(
                         'User.id' => $this->Session->read( 'Auth.User.id' )
                    ),
                     'contain' => false
                )
            );
            $cui['User'] = $user['User'];
            $this->set( 'cui', $cui);

            $this->assert( !empty( $cui ), 'error404' );
            
            /*********/
            
            // On transforme les champs de type date en format JJ/MM/AAAA
            $schema = $this->Cui->schema();
            foreach( $schema as $field => $params ) {
                if( $params['type'] === 'date' ) {
                    $value = Hash::get( $cui, "Cui.{$field}" );
                    if( $value !== null ) {
                        $cui = Hash::insert( $cui, "Cui.{$field}", date_short($value) );
                    }
                }
            }

            App::uses( 'DefaultUtility', 'Default.Utility' );
            $mailBodySend = '';
            if( !empty($cui['Textmailcui66']['contenu']) ) {
                $mailBodySend = DefaultUtility::evaluate( $cui, $cui['Textmailcui66']['contenu'] );
            }
            $this->set( 'mailBodySend', $mailBodySend);
            
            /**********/
            
            // Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}
            
            if( !empty( $this->request->data) ) {
                
                $this->Cui->begin();

                if( !isset( $cui['Partenaire']['email'] ) || empty( $cui['Partenaire']['email'] ) ) {
                    $this->Session->setFlash( "Mail non envoyé: adresse mail de l'employeur ({$cui['Partenaire']['libstruc']}) non renseignée.", 'flash/error' );
                    $this->redirect( $this->referer() );
                }

                $mailBody = DefaultUtility::evaluate( $cui, $cui['Textmailcui66']['contenu'] );

                // Envoi du mail
                $success = true;
                try {
                    $configName = WebrsaEmailConfig::getName( 'mail_employeur_cui' );
                    $Email = new CakeEmail( $configName );

                    // Choix du destinataire suivant le niveau de debug
                    if( Configure::read( 'debug' ) == 0 ) {
                        $Email->to( $cui['Partenaire']['email'] );
                    }
                    else {
                        $Email->to( WebrsaEmailConfig::getValue( 'mail_employeur_cui', 'to', $Email->from() ) );
                    }

                    $Email->subject( WebrsaEmailConfig::getValue( 'mail_employeur_cui', 'subject', 'Demande de CUI' ) );
    //				$mailBody = "Bonjour,\n\n {$cui['Personne']['qual']} {$cui['Personne']['nom']} {$cui['Personne']['prenom']}, bénéficiaire du rSa socle, donc éligible au dispositif des contrats aidés peut être recruté par votre structure.";
                    $mailBody = DefaultUtility::evaluate( $cui, $cui['Textmailcui66']['contenu'] );

                    $result = $Email->send( $mailBody );
                    $success = !empty( $result ) && $success;
                } catch( Exception $e ) {
                    $this->log( $e->getMessage(), LOG_ERROR );
                    $success = false;
                }
                
                if( $success ) {
                    $success = $this->Cui->updateAllUnBound(
                            array( 'Cui.dateenvoimail' => '\''.date_cakephp_to_sql( $this->request->data['Cui']['dateenvoimail'] ).'\'' ),
                            array(
                                '"Cui"."personne_id"' => $personne_id,
                                '"Cui"."id"' => $cui['Cui']['id']
                            )
                        ) && $success;
                }

                if( $success ) {
                    $this->User->commit();
                    $this->Jetons2->release( $dossier_id );
                    $this->Session->setFlash( 'Mail envoyé', 'flash/success' );
                    $this->redirect( array( 'action' => 'index', $personne_id ) );
                }
                else {
                    $this->User->rollback();
                    $this->Session->setFlash( 'Mail non envoyé', 'flash/error' );
                }

                $this->render( 'maillink' );
                $this->set( 'urlmenu', '/cuis/index/'.$personne_id );
    //			$this->redirect( $this->referer() );

            }
        }
        
        
		/**
		 * Imprime une synthèse du CUI.
		 *
		 * @param integer $id L'id du CUI que l'on veut imprimer
		 * @return void
		 */
		public function synthesecui66( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Cui->personneId( $id ) ) );

			$pdf = $this->Cui->getSynthesecui66Pdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'synthese_cui_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le document de synthèse du contrat unique d\'insertion.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>
