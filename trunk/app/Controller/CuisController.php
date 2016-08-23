<?php
	/**
	 * Code source de la classe CuisController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'CakeEmail', 'Network/Email' );
	App::uses( 'WebrsaEmailConfig', 'Utility' );

	/**
	 * La classe CuisController permet de gérer les CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Controller
	 * @see Cuis66Controller (refonte)
	 */
	class CuisController extends AppController
	{
		public $name = 'Cuis';

		public $uses = array( 'Cui', 'Option', 'Departement', 'WebrsaCui' );

		public $helpers = array( 
			'Default', 
			'Default2', 
			'Locale', 
			'Csv', 
			'Xform', 
			'Fileuploader', 
			'Cake1xLegacy.Ajax',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
		);

		public $components = array(
			'Default',
			'Fileuploader',
			'Gedooo.Gedooo',
			'Jetons2',
			'DossiersMenus',
			'Search.SearchPrg' => array(
				'actions' => array( 'search' )
			),
			'WebrsaAccesses'
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
			'exportcsv' => 'read',
			'filelink' => 'read',
			'fileview' => 'read',
			'impression' => 'read',
			'index' => 'read',
			'indexparams' => 'read',
			'valider' => 'update',
			'view' => 'read',
			'search' => 'read',
			'synthesecui66' => 'read',
		);

		public $aucunDroit = array( 'ajaxtaux','ajaxfileupload', 'impression', 'fileview', 'download', 'ajaxemployeur' );

		public $commeDroit = array(
			'add' => 'Cuis:edit',
			'view' => 'Cuis:index',
			'search' => 'Criterescuis:search',
			'exportcsv' => 'Criterescuis:exportcsv',
		);

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 * @deprecated since version 2.9.0
		 */
		protected function _setOptions() {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
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
			$this->set( 'rolepers', ClassRegistry::init('Prestation')->enum('rolepers') );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', ClassRegistry::init('Personne')->enum('nati') );

			$dept = $this->Departement->find( 'list', array( 'fields' => array( 'numdep', 'name' ), 'contain' => false ) );
			$this->set( compact( 'dept' ) );



			if( Configure::read( 'CG.cantons' ) ) {
				$Canton = ClassRegistry::init( 'Canton' );
				$this->set( 'cantons', $Canton->selectList() );
			}


			$this->set( 'prestataires', $this->Cui->Referent->WebrsaReferent->listOptions() );
			$this->set( 'referents', $this->Cui->Referent->find( 'list', array( 'recursive' => false ) ) );

			$this->set( 'structs', $this->Cui->Structurereferente->listeParType( array( 'cui' => true ) ) );


			$this->set( 'rsaSocle', ClassRegistry::init('Detailcalculdroitrsa')->enum('natpf') );

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
		 * @deprecated since version 2.9.0
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
		 * @deprecated since version 2.9.0
		 */
		public function indexparams() {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
		}

		/**
		 * Envoi d'un fichier temporaire depuis le formualaire.
		 */
		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		 * Suppression d'un fichier temporaire.
		 */
		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		 * Visualisation d'un fichier temporaire.
		 *
		 * @param integer $id
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 * Visualisation d'un fichier stocké.
		 *
		 * @param integer $id
		 */
		public function download( $id ) {
			$this->Fileuploader->download( $id );
		}

		/**
		 * Liste des fichiers liés à une orientation.
		 *
		 * @param integer $cui_id
		 */
		public function filelink( $cui_id ) {
			$personne_id = $this->Cui->personneId( $cui_id );
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->set( compact( 'dossierMenu' ) );

			$this->Fileuploader->filelink( $cui_id, array( 'action' => 'index', $personne_id ) );
			$this->set( 'urlmenu', "/cuis/index/{$personne_id}" );

			$options = $this->Cui->enums();
			$this->set( compact( 'options' ) );
		}

		/**
		 * Liste des CUIs appartenant à un allocataire donné.
		 *
		 * @param integer $personne_id
		 * @deprecated since version 2.9.0
		 */
		public function index_old( $personne_id = null ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$this->_setEntriesAncienDossier( $personne_id, 'Cui' );

			// Précondition: La personne est-elle bien en Rsa Socle ?
			$alerteRsaSocle = $this->Cui->_prepare( $personne_id );
			$this->set( 'alerteRsaSocle', $alerteRsaSocle );

            // Alerte à afficher si le titre de séjour se termine bientôt
            $alerteTitreSejour = $this->Cui->Personne->WebrsaPersonne->nbMoisAvantFinTitreSejour($personne_id);
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
                    'order' => array( 'Cui.signaturele DESC', 'Cui.id DESC' )
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
		 * @deprecated since version 2.9.0
		 */
		public function add_old( $personne_id ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un CUI donné.
		 *
		 * @param integer $id
		 * @deprecated since version 2.9.0
		 */
		public function edit_old( $id ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


        /**
		 * Traitement du formulaire d'ajout ou de modification de CUI.
		 *
		 * @param inetger $id Correspond à l'id de la Personne en cas d'ajout, à l'id du Cui en cas de modification.
		 * @deprecated since version 2.9.0
		 */
		protected function _add_edit( $id = null ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
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

            // Informations du dernier CUI signé
            $dernierCui = $this->Cui->dernierCui( $personne_id );
            $this->set('dernierCui', $dernierCui);

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

//                        if( $positioncui66 == 'traite') {
//                            $success = $this->Cui->updateAllUnBound(
//                                array( 'Cui.decisioncui' => '\'sanssuite\'' ),
//                                array(
//                                    '"Cui"."personne_id"' => $personne_id,
//                                    '"Cui"."id"' => $data['Cui']['id']
//                                )
//                            ) && $success;
//                        }
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

            // Liste des modèles de mail de relance pour les employeurs paramétrés  dans l'application
            $textsmailscuis66relances = $this->Cui->Textmailcui66relance->find('list');
            $this->set( 'textsmailscuis66relances', $textsmailscuis66relances);


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
		 * @deprecated since version 2.9.0
		 */
		public function valider( $cui_id = null ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
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
		 * @deprecated since version 2.9.0
		 */
		public function impression( $id ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
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
		 * @deprecated since version 2.9.0
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Cui->personneId( $id ) ) );

			$this->Default->delete( $id );
		}

		/**
		 * Visualisation d'un CUI.
		 *
		 * @param integer $id
		 * @deprecated since version 2.9.0
		 */
		public function view_old( $id ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
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
			$personne = $this->{$this->modelClass}->Personne->WebrsaPersonne->detailsApre( $cui['Cui']['personne_id'], $this->Session->read( 'Auth.User.id' ) );
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
		 * @deprecated since version 2.9.0
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
		 * @deprecated since version 2.9.0
		 */
		public function maillink( $id = null ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
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
                        $this->Cui->Textmailcui66->fields(),
                        $this->Cui->Textmailcui66relance->fields()
                    ),
                    'conditions' => array(
                        'Cui.id' => $id
                    ),
                    'joins' => array(
                        $this->Cui->join( 'Personne', array( 'type' => 'INNER' ) ),
                        $this->Cui->join( 'Partenaire', array( 'type' => 'INNER') ),
                        $this->Cui->join( 'Textmailcui66', array( 'type' => 'INNER') ),
                        $this->Cui->join( 'Textmailcui66relance', array( 'type' => 'LEFT OUTER') )
                    ),
                    'contain' => false
                )
            );

			$this->assert( !empty( $cui ), 'error404' );

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

            $mailBodyRelance = '';
            if( !empty($cui['Textmailcui66relance']['contenu']) ) {
                $mailBodyRelance = DefaultUtility::evaluate( $cui, $cui['Textmailcui66relance']['contenu'] );
            }
            $this->set( 'mailBodyRelance', $mailBodyRelance);

            /**********/

            // Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}


            // Quelle est la liste des pièces jointes associée aux mails du CUI
            // Liste des pièces associées au CUI
            /*$piecesmailscuis66 = $this->Cui->CuiPiecemailcui66->find(
                'all',
                array(
                    'fields' => array(
                        'CuiPiecemailcui66.piecemailcui66_id'
                    ),
                    'conditions' => array(
                        'CuiPiecemailcui66.cui_id' => $id
                    )
                )
            );
            $piecesMails = Hash::extract($piecesmailscuis66, '{n}.CuiPiecemailcui66.piecemailcui66_id');


            // Récupération des fichiers liés selon l'(es) id(s) de(s) la pièce(s) associée(s) au CUI
            $pieceJointes = $this->Cui->Piecemailcui66->find(
                'all',
                array(
                    'conditions' => array(
                      'Piecemailcui66.id' => $piecesMails
                    ),
                    'contain' => array(
                        'Fichiermodule' => array(
                            'fields' => array( 'name', 'id', 'document')
                        )
                    )
                )
            );*/

            if( !empty( $this->request->data) ) {

                $this->Cui->begin();

                if( !isset( $cui['Partenaire']['email'] ) || empty( $cui['Partenaire']['email'] ) ) {
                    $this->Session->setFlash( "Mail non envoyé: adresse mail de l'employeur ({$cui['Partenaire']['libstruc']}) non renseignée.", 'flash/error' );
                }

//                if( ( $cui['Cui']['sendmailemployeur'] == '1' ) && !isset( $this->request->data['Cui']['textmailcui66relance_id'] ) ) {
//                    $this->Session->setFlash( "Mail non envoyé: le premier mail a été envoyé et le courrier de relance n'a pas été sélectionné.", 'flash/error' );
//                }

                if( $cui['Cui']['sendmailemployeur'] != '1' ) { //CAs du premier mail
                    $mailBody = DefaultUtility::evaluate( $cui, $cui['Textmailcui66']['contenu'] );

                    // Envoi du mail
                    $success = true;
                    try {
                        $configName = WebrsaEmailConfig::getName( 'mail_employeur_cui' );
                        $Email = new CakeEmail( $configName );

                        // Choix du destinataire suivant suivant l'environnement
                        if( !WebrsaEmailConfig::isTestEnvironment() ) {
                            $Email->to( $cui['Partenaire']['email'] );
                        }
                        else {
                            $Email->to( WebrsaEmailConfig::getValue( 'mail_employeur_cui', 'to', $Email->from() ) );
                        }

                        $Email->subject( WebrsaEmailConfig::getValue( 'mail_employeur_cui', 'subject', 'Demande de CUI' ) );
                        $mailBody = DefaultUtility::evaluate( $cui, $cui['Textmailcui66']['contenu'] );

                        // Ajout de pièces jointes au mail envoyé
                        /*foreach( $pieceJointes as $i => $piecejointe) {
                            if( !empty($piecejointe['Fichiermodule']) ) {
                                $piecejointe['Fichiermodule'][0]['file'] = $piecejointe['Fichiermodule'][0]['document'];
                                $Email->attachments( array('data' => $piecejointe['Fichiermodule'][0] ) );
                            }
                        }*/

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
                else if( isset($cui['Cui']['textmailcui66relance_id'])) { //CAs de la relance
                    // Envoi du mail de relance
                    $mailBodyRelance = DefaultUtility::evaluate( $cui, $cui['Textmailcui66relance']['contenu'] );
//                    debug($mailBodyRelance);
//                    die();
                    // Envoi du mail de relance
                    $success = true;
                    try {
                        $configName = WebrsaEmailConfig::getName( 'mail_employeur_cui' );
                        $Email = new CakeEmail( $configName );

                        // Choix du destinataire suivant suivant l'environnement
                        if( !WebrsaEmailConfig::isTestEnvironment() ) {
                            $Email->to( $cui['Partenaire']['email'] );
                        }
                        else {
                            $Email->to( WebrsaEmailConfig::getValue( 'mail_employeur_cui', 'to', $Email->from() ) );
                        }

                        $Email->subject( WebrsaEmailConfig::getValue( 'mail_employeur_cui', 'subject', 'Demande de CUI' ) );
                        $mailBodyRelance = DefaultUtility::evaluate( $cui, $cui['Textmailcui66relance']['contenu'] );

                        // Ajout de pièces jointes au mail envoyé
                        /*foreach( $pieceJointes as $i => $piecejointe) {
                            if( !empty($piecejointe['Fichiermodule']) ) {
                                $piecejointe['Fichiermodule'][0]['file'] = $piecejointe['Fichiermodule'][0]['document'];
                                $Email->attachments( array('data' => $piecejointe['Fichiermodule'][0] ) );
                            }
                        }*/

                        $result = $Email->send( $mailBodyRelance );
                        $success = !empty( $result ) && $success;
                    } catch( Exception $e ) {
                        $this->log( $e->getMessage(), LOG_ERROR );
                        $success = false;
                    }

                    if( $success ) {
                        $success = $this->Cui->updateAllUnBound(
                                array( 'Cui.dateenvoirelance' => '\''.date_cakephp_to_sql( $this->request->data['Cui']['dateenvoirelance'] ).'\'' ),
                                array(
                                    '"Cui"."personne_id"' => $personne_id,
                                    '"Cui"."id"' => $cui['Cui']['id']
                                )
                            ) && $success;
                    }

                    if( $success ) {
                        $this->User->commit();
                        $this->Jetons2->release( $dossier_id );
                        $this->Session->setFlash( 'Mail de relance envoyé', 'flash/success' );
                        $this->redirect( array( 'action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->User->rollback();
                        $this->Session->setFlash( 'Mail de relance non envoyé', 'flash/error' );
                    }

                    $this->render( 'maillink' );
                    $this->set( 'urlmenu', '/cuis/index/'.$personne_id );
                }
            }
        }


		/**
		 * Imprime une synthèse du CUI.
		 *
		 * @param integer $id L'id du CUI que l'on veut imprimer
		 * @return void
		 * @deprecated since version 2.9.0
		 */
		public function synthesecui66( $id ) {
			trigger_error("Utilisation d'une méthode dépréciée : ".__CLASS__.'::'.__FUNCTION__, E_USER_DEPRECATED);
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
		
		/**
		 * Moteur de recherche
		 */
		public function search() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesCuis' );
			$Recherches->search();
			$this->Cui->validate = array();
			$this->Cui->Cui66->validate = array();
			$this->Cui->Cui66->Decisioncui66->validate = array();
			$this->Cui->Partenairecui->Adressecui->validate = array();
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesCuis' );
			$Recherches->exportcsv();
		}
		
		/**
		 * Début Intégration Cuis66Controller
		 * les fonctions qui portent le même nom ont été renommé avec un suffix "_old"
		 */
		
		/**
		 * Liste des CUI du bénéficiaire.
		 * 
		 * @param integer $personne_id
		 */
		public function index( $personne_id ) {
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );

			$this->_setEntriesAncienDossier( $personne_id, 'Cui' );
			
			$results = $this->WebrsaAccesses->getIndexRecords($personne_id, $this->Cui->queryIndex($personne_id));
			
			$messages = $this->Cui->messages( $personne_id );
			$addEnabled = $this->Cui->addEnabled( $messages );

			// Options
			$options = $this->Cui->options($this->Session->read( 'Auth.User.id' ));

			$this->set(
				compact('results', 'dossierMenu', 'messages', 'addEnabled', 'personne_id', 'options', 'isRsaSocle')
			);
			
			switch ((int)Configure::read('Cg.departement')) {
				case 66: $this->view = __FUNCTION__.'_cg66'; break;
			}
		}
		
		/**
		 * Formulaire d'ajout de fiche de CUI
		 *
		 * @param integer $personne_id L'id de la Personne à laquelle on veut ajouter un CUI
		 */
		public function add( $personne_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Méthode générique d'ajout et de modification de CUI
		 *
		 * @param integer $id L'id de la personne (add) ou du CUI (edit)
		 */
		public function edit( $id = null ) {
			if( $this->action === 'add' ) {
				$personne_id = $id;
				$id = null;
				$this->WebrsaAccesses->check(null, $personne_id);
			}
			else {
				$personne_id = $this->Cui->personneId( $id );
				$this->WebrsaAccesses->check($id);
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );
			
			// INFO: champ non obligatoire
			unset( $this->Cui->Entreeromev3->validate['familleromev3_id']['notEmpty'] );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// On tente la sauvegarde
			if( !empty( $this->request->data ) ) {
				$this->Cui->begin();
				if( $this->Cui->saveAddEdit( $this->request->data, $this->Session->read( 'Auth.User.id' ) ) ) {
					$this->Cui->commit();
					$cui_id = $this->Cui->id;
					$this->Cui->updatePositionsCuisById( $cui_id );
					$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cui->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $this->Cui->prepareFormDataAddEdit( $personne_id, $id );
			}
			
			// Options
			$options = $this->Cui->options($this->Session->read( 'Auth.User.id' ));

			$urlmenu = "/cuis/index/{$personne_id}";
			
			$queryPersonne = $this->Cui->queryPersonne( $personne_id );
			$this->Cui->Personne->forceVirtualFields = true;
			$personne = $this->Cui->Personne->find( 'first', $queryPersonne );

			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu', 'personne' ) );
			
			switch ((int)Configure::read('Cg.departement')) {
				case 66: 
					$this->view = __FUNCTION__.'_cg66';
					$this->set('mailEmployeur', $this->action !== 'add');
					$this->set('correspondancesChamps', json_encode($this->Cui->Partenairecui->Partenairecui66->correspondancesChamps));
					break;
				default: $this->view = __FUNCTION__;
			}
		}
		
		/**
		 * Vue d'un CUI
		 * 
		 * @param type $id
		 */
		public function view( $id = null ) {
			$this->WebrsaAccesses->check($id);
			$personne_id = $this->Cui->personneId( $id );

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );
			
			$query = $this->Cui->queryView( $id );
			$this->request->data = $this->Cui->find( 'first', $query );
					
			// Options
			$options = $this->Cui->options();
			
			$urlmenu = "/cuis/index/{$personne_id}";

			$Allocataire = ClassRegistry::init( 'Allocataire' );
			
			$queryPersonne = $Allocataire->searchQuery();
			$queryPersonne['conditions']['Personne.id'] = $personne_id;
			$fields = array(
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Personne.nir',
				'Personne.nomcomnai',
				'Personne.nati',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.codepos',
				'Adresse.lieudist',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.nomcom',
				'Adresse.canton',				
				'Dossier.matricule',
				'Dossier.dtdemrsa',
				'Dossier.fonorg',
				'Referentparcours.nom_complet' => $queryPersonne['fields']['Referentparcours.nom_complet'],
				'Titresejour.dftitsej'
			);
			$queryPersonne['fields'] = $fields;
			
			// Jointure spéciale adresse actuelle / département pour obtenir le nom du dpt
			$queryPersonne['fields'][] = 'Departement.name';
			$queryPersonne['joins'][] = array(
				'table' => 'departements',
				'alias' => 'Departement',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'SUBSTRING( Adresse.codepos FROM 1 FOR 2 ) = Departement.numdep'
				)
			);
			$queryPersonne['joins'][] = array(
				'table' => 'titressejour',
				'alias' => 'Titresejour',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Titresejour.personne_id' => $personne_id
				)
			);
			
			$personne = $this->Cui->Personne->find('first', $queryPersonne);
			$personne['Foyer']['nb_enfants'] = $this->Cui->Personne->Prestation->getNbEnfants( $personne_id );

			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu', 'personne' ) );
			
			switch ((int)Configure::read('Cg.departement')) {
				case 66: 
					$this->view = __FUNCTION__.'_cg66';
					break;
				default: $this->view = __FUNCTION__;
			}
		}
		
		/**
		 * Fin Intégration Cuis66Controller
		 */
	}
?>