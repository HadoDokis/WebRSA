<?php
	/**
	 * Code source de la classe Dossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	 App::uses('ZipUtility', 'Utility');
	 App::uses('WebrsaPdfUtility', 'Utility');

	/**
	 * La classe Dossierspcgs66Controller ... (CG 66).
	 *
	 * @package app.Controller
	 */
	class Dossierspcgs66Controller extends AppController
	{
		public $helpers = array( 
			'Default', 
			'Default2', 
			'Cake1xLegacy.Ajax', 
			'Fileuploader',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
		);

		public $uses = array( 'Dossierpcg66', 'Option', 'Typenotifpdo', 'Decisionpdo', 'WebrsaDossierpcg66' );

		public $components = array( 
			'Cohortes', 
			'Fileuploader', 
			'Gedooo.Gedooo', 
			'Jetons2', 
			'DossiersMenus',
			'Search.SearchPrg' => array(
				'actions' => array( 
					'search', 
					'search_gestionnaire',
					'search_affectes',
					'cohorte_imprimer',
					'cohorte_enattenteaffectation' => array(
						'filter' => 'Search'
					),
					'cohorte_atransmettre' => array(
						'filter' => 'Search'
					),
					'cohorte_heberge' => array(
						'filter' => 'Search'
					),
					'cohorte_rsamajore' => array(
						'filter' => 'Search'
					),
				)
			),
		);

		public $commeDroit = array(
			'add' => 'Dossierspcgs66:edit',
			'view' => 'Dossierspcgs66:index',
			'search' => 'Criteresdossierspcgs66:dossier',
			'search_gestionnaire' => 'Criteresdossierspcgs66:gestionnaire',
			'exportcsv' => 'Criteresdossierspcgs66:exportcsv',
			'exportcsv_gestionnaire' => 'Criteresdossierspcgs66:exportcsv',
			'cohorte_enattenteaffectation' => 'Cohortesdossierspcgs66:enattenteaffectation',
			'imprimer' => 'Decisionsdossierspcgs66::decisionproposition',
			'search_affectes' => 'Cohortesdossierspcgs66:affectes',
			'cohorte_imprimer' => 'Cohortesdossierspcgs66:aimprimer',
			'cohorte_atransmettre' => 'Cohortesdossierspcgs66:atransmettre',
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download', 'ajaxetatpdo', 'ajax_getetatdossierpcg66' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxetatpdo' => 'read',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'cohorte_atransmettre' => 'update',
			'cohorte_enattenteaffectation' => 'update',
			'cohorte_rsamajore' => 'update',
			'cohorte_heberge' => 'update',
			'cohorte_imprimer' => 'update',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'exportcsv' => 'read',
			'exportcsv_gestionnaire' => 'read',
			'fileview' => 'read',
			'imprimer' => 'update',
			'index' => 'read',
			'search' => 'read',
			'search_affectes' => 'read',
			'search_gestionnaire' => 'read',
			'view' => 'read',
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$options = $this->Dossierpcg66->enums();

			$this->set( 'etatdosrsa', ClassRegistry::init('Dossier')->enum('etatdosrsa') );
			$this->set( 'pieecpres', ClassRegistry::init('Personne')->enum('pieecpres') );
			$this->set( 'rolepers', ClassRegistry::init('Prestation')->enum('rolepers') );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'motifpdo', ClassRegistry::init('Propopdo')->enum('motifpdo') );
			$this->set( 'categoriegeneral', ClassRegistry::init('Contratinsertion')->enum('sect_acti_emp') );
			$this->set( 'categoriedetail', ClassRegistry::init('Contratinsertion')->enum('emp_occupe') );

			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'typepdo', $this->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list', array( 'order' => 'Decisionpdo.libelle ASC' ) ) );

			$this->set( 'originepdo', $this->Dossierpcg66->Originepdo->find( 'list' ) );

			$this->set( 'serviceinstructeur', $this->Dossierpcg66->Serviceinstructeur->listOptions() );
			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );

            $gestionnaires = $this->User->find(
                'all',
                array(
                    'fields' => array(
                        'User.nom_complet',
                        '( "Poledossierpcg66"."id" || \'_\'|| "User"."id" ) AS "User__gestionnaire"',
                    ),
                    'conditions' => array(
                        'User.isgestionnaire' => 'O'
                    ),
                    'joins' => array(
                        $this->User->join( 'Poledossierpcg66', array( 'type' => 'INNER' ) ),
                    ),
                    'order' => array( 'User.nom ASC', 'User.prenom ASC' ),
                    'contain' => false
                )
            );
            $gestionnaires = Hash::combine( $gestionnaires, '{n}.User.gestionnaire', '{n}.User.nom_complet' );
            $this->set( compact( 'gestionnaires' ) );

            $this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						),
                        'order' => array( 'User.nom ASC', 'User.prenom ASC' )
					)
				)
			);

            $this->set(
                'polesdossierspcgs66',
                $this->User->Poledossierpcg66->find(
                    'list',
                    array(
                        'fields' => array(
                            'Poledossierpcg66.id',
                            'Poledossierpcg66.name'
                        ),
                        'conditions' => array( 'Poledossierpcg66.isactif' => '1' ),
                        'order' => array( 'Poledossierpcg66.name ASC' )
                    )
                )
            );

			$options = Set::merge(
				$this->Dossierpcg66->Decisiondossierpcg66->enums(),
				$options
			);

			$options = Hash::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
			$this->set( compact( 'options' ) );
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
		 * @param integer $id
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 * Téléchargement des fichiers préalablement associés
		 *
		 * @param integer $fichiermodule_id
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}
		
		/**
		 * Permet de recalculer l'etat d'un dossier pcg et d'obtenir la nouvelle valeur
		 * 
		 * @param type $id du Dossierpcg66
		 */
		public function ajax_getetatdossierpcg66( $id = null ) {
			if ( $id === null ) {
				$etatdossierpcg = null;
				$datetransmission = null;
				$orgs = null;

				$this->set( compact( 'etatdossierpcg', 'datetransmission', 'orgs' ) );
				$this->render( 'ajaxetatpdo', 'ajax' );
			}
			
			$this->Dossierpcg66->WebrsaDossierpcg66->updatePositionsPcgsById($id);
			
			$sqOrgs = str_replace('Decisiondossierpcg66', 'decision', $this->Dossierpcg66->Decisiondossierpcg66->sq(
				array(
					'fields' => 'Orgtransmisdossierpcg66.name',
					'joins' => array(
						$this->Dossierpcg66->Decisiondossierpcg66->join('Decdospcg66Orgdospcg66'),
						$this->Dossierpcg66->Decisiondossierpcg66->Decdospcg66Orgdospcg66->join('Orgtransmisdossierpcg66'),
					),
					'conditions' => array(
						'Decisiondossierpcg66.dossierpcg66_id = Dossierpcg66.id',
						'Decisiondossierpcg66.validationproposition' => 'O'
					),
				)
			));
			
			$query = array(
				'fields' => array(
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.datetransmissionop',
					"(ARRAY_TO_STRING(ARRAY({$sqOrgs}), ', ')) AS \"Notificationdecisiondossierpcg66__name\""
				),
				'conditions' => array(
					'Dossierpcg66.id' => $id
				),
				'joins' => array(
					$this->Dossierpcg66->join('Decisiondossierpcg66'),
				),
				'order' => array(
					'Decisiondossierpcg66.id' => 'DESC'
				),
				'contain' => false
			);
			$result = $this->Dossierpcg66->find('first', $query);
			
			$etatdossierpcg = Hash::get($result, 'Dossierpcg66.etatdossierpcg');
			$datetransmission = Hash::get($result, 'Decisiondossierpcg66.datetransmissionop');
			$orgs = Hash::get($result, 'Notificationdecisiondossierpcg66.name' );
			
			$this->set( compact( 'etatdossierpcg', 'datetransmission', 'orgs' ) );
			$this->render( 'ajaxetatpdo', 'ajax' );
		}

		/**
		 * Liste des dossiers PCG d'un foyer
		 * 
		 * @param integer $foyer_id
		 */
		public function index( $foyer_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer_id ) ) );

			$personneDem = $this->WebrsaDossierpcg66->findPersonneDem($foyer_id);
			
			$results = $this->WebrsaDossierpcg66->getIndexData( $foyer_id );
			
			$this->set( compact( 'personneDem', 'results', 'foyer_id' ) );
			$this->_setOptions();
		}

		/**
		 * @obsolete
		 */
//		public function add() {
//			$args = func_get_args();
//			call_user_func_array( array( $this, '_add_edit' ), $args );
//		}

		/**
		 * @obsolete
		 */
//		public function edit() {
//			$args = func_get_args();
//			call_user_func_array( array( $this, '_add_edit' ), $args );
//		}
		
		/**
		 * Action d'ajout d'un dossier pcg
		 * 
		 * @param type $foyer_id
		 */
		public function add( $foyer_id ) {
			// Initialisation
			$this->_init_add_edit($foyer_id);
			
			// Sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->_save_add_edit();
			}
			
			// Modification du request data uniquement à la fin
			$this->set( 'personnedecisionmodifiable', $this->_isDecisionModifiable($foyer_id) );
			
			// Vue
			$this->view = 'edit';
		}
		
		/**
		 * Action d'edition d'un dossier pcg
		 * 
		 * @param type $dossierpcg66_id
		 */
		public function edit( $dossierpcg66_id ) {
			// Initialisation
			$foyer_id = $this->Dossierpcg66->field( 'foyer_id', array( 'id' => $dossierpcg66_id ) );
			$this->_init_add_edit($foyer_id);
			
			// Récupération de données
			$dossierpcg66 = $this->WebrsaDossierpcg66->findDossierpcg($dossierpcg66_id);
			$this->assert( !empty( $dossierpcg66 ), 'invalidParameter' );
			$personnespcgs66 = $this->WebrsaDossierpcg66->findPersonnepcg($dossierpcg66_id);
			$decisionsdossierspcgs66 = $this->WebrsaDossierpcg66->findDecisiondossierpcg($dossierpcg66_id);
			$fichiersEnBase = Hash::extract( $this->WebrsaDossierpcg66->findFichiers($dossierpcg66_id), '{n}.Fichiermodule' );
			
			// Variables pour la vue
			$etatdossierpcg = Hash::get($dossierpcg66, 'Dossierpcg66.etatdossierpcg');
			$lastDecisionId = Hash::get($decisionsdossierspcgs66, '0.Decisiondossierpcg66.id');
			$ajoutDecision = Hash::get($decisionsdossierspcgs66, '0.Decisiondossierpcg66.validationproposition') !== null;
			$this->set( 
				compact( 
					'ajoutDecision', 
					'lastDecisionId', 
					'decisionsdossierspcgs66', 
					'personnespcgs66', 
					'dossierpcg66_id', 
					'etatdossierpcg', 
					'fichiersEnBase',
					'dossierpcg66'
				) 
			);

			// Sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->_save_add_edit();
			}
			else {
				$this->request->data = $dossierpcg66;
			}
			
			// Modification du request data uniquement à la fin
			$this->set( 'personnedecisionmodifiable', $this->_isDecisionModifiable( $foyer_id, $etatdossierpcg ) );
			$this->request->data['Dossierpcg66']['user_id'] = $dossierpcg66['Dossierpcg66']['poledossierpcg66_id'].'_'.$dossierpcg66['Dossierpcg66']['user_id'];
		}
		
		/**
		 * Initialisation du formulaire d'edition d'un dossier pcg
		 * Informations sur le demandeur, jeton, redirection en cas de retour
		 * 
		 * @todo $gestionnairemodifiable est inutile, vérifier son utilité initiale, le retirer ?
		 * @param integer $foyer_id
		 */
		protected function _init_add_edit( $foyer_id ) {
			// Validité de l'url
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );
			
			//Gestion des jetons
			$dossier_id = $this->Dossierpcg66->Foyer->dossierId( $foyer_id );
			$this->Jetons2->get( $dossier_id );
			
			// Redirection si Cancel
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $foyer_id ) );
			}
			
			// Récupération de données
			$personneDem = $this->WebrsaDossierpcg66->findPersonneDem($foyer_id);
			
			// Variables pour la vue
			$gestionnairemodifiable = true;
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer_id ) ) );
			$this->set( compact( 'personneDem', 'gestionnairemodifiable', 'foyer_id', 'dossier_id' ) );
			$this->_setOptions();
		}
		
		/**
		 * Sauvegarde d'un formulaire add ou edit
		 */
		protected function _save_add_edit() {
			$this->Dossierpcg66->begin();
			
			if ( !Hash::get($this->request->data, 'Dossierpcg66.etatdossierpcg') ) {
				$this->request->data['Dossierpcg66']['etatdossierpcg'] = 'attaffect';
			}

			$saved = $this->Dossierpcg66->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) );
			$etatdossierpcg = Hash::get($this->viewVars, 'dossierpcg66.Dossierpcg66.etatdossierpcg');
			$etatFinal = in_array( $etatdossierpcg, array( 'annule', 'traite', 'decisionvalid', 'transmisop' ) );
			$id = $saved ? $this->Dossierpcg66->id : Hash::get($this->viewVars, 'dossierpcg66.Dossierpcg66.id');
			$decisiondefautinsertionep66_id = Hash::get(
				$this->viewVars, 'dossierpcg66.Dossierpcg66.decisiondefautinsertionep66_id'
			);

			/**
			 * INFO : Passe l'etat d'une EP Audition transformé en EP Parcours 
			 * en "traité" si EP Parcours n'est pas traité alors que le Dossierpcg est validé.
			 * En pratique ça n'arrive jamais et je ne comprend pas l'utilité de ce processus...
			 * Ne semble pas avoir de conséquences.
			 */
			if ( $saved && $etatFinal && $decisiondefautinsertionep66_id ) {
				$saved = $this->Dossierpcg66->WebrsaDossierpcg66->updateEtatPassagecommissionep( $decisiondefautinsertionep66_id );
			}

			if( $saved && $this->_saveFichiers($id) ) {
				$this->Dossierpcg66->commit();
				$this->Jetons2->release( $this->viewVars['dossier_id'] );
				$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
				$this->redirect( array(  'controller' => 'dossierspcgs66','action' => 'index', $this->viewVars['foyer_id'] ) );
			}
			else {
				$id && $this->set('fichiers', $this->Fileuploader->fichiers( $id ));
				$this->Dossierpcg66->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
			}
		}
		
		/**
		 * Sauvegarde des fichiers liés
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		protected function _saveFichiers( $id ) {
			$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
			return $this->Fileuploader->saveFichiers(
				$dir,
				!Set::classicExtract( $this->request->data, "Dossierpcg66.haspiecejointe" ),
				$id
			);
		}
		
		/**
		 * Décide si on affiche la partie "décision" du formulaire
		 * Attention, rempli le request->data
		 * 
		 * @param integer $foyer_id
		 * @param string $etatdossierpcg
		 * @return boolean
		 */
		protected function _isDecisionModifiable( $foyer_id, $etatdossierpcg = '' ) {
			// Récupération du gestionnaire précédent et remplissage de la liste déroulante avec cette valeur par défaut
            $dossierpcg66Pcd = $this->Dossierpcg66->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossierpcg66.foyer_id' => $foyer_id
                    ),
                    'recursive' => -1,
                    'order' => array( 'Dossierpcg66.created DESC'),
                    'limit' => 1
                )
            );

            if( !empty( $dossierpcg66Pcd ) && in_array( $etatdossierpcg, array( '', 'attaffect' ) ) ) {
                $this->request->data['Dossierpcg66']['poledossierpcg66_id'] = $dossierpcg66Pcd['Dossierpcg66']['poledossierpcg66_id'];
                $this->request->data['Dossierpcg66']['user_id'] = $dossierpcg66Pcd['Dossierpcg66']['poledossierpcg66_id'].'_'.$dossierpcg66Pcd['Dossierpcg66']['user_id'];
                $this->request->data['Dossierpcg66']['etatdossierpcg'] = 'attinstr';
            }
			
			return !in_array($etatdossierpcg, array( '', 'attaffect' ));
		}

		/**
		 *
		 * @deprecated since version 3.0
		 * @param integer $id
		 */
		protected function _add_edit( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			if( $this->action == 'edit' ) {
				$foyer_id = $this->Dossierpcg66->field( 'foyer_id', array( 'id' => $id ) );
			}
			else {
				$foyer_id = $id;
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer_id ) ) );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$dossier_id = $this->Dossierpcg66->Foyer->dossierId( $foyer_id );
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $foyer_id ) );
			}
			$fichiers = array();
			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$foyer_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$dossierpcg66_id = $id;
				$dossierpcg66 = $this->Dossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Dossierpcg66.id' => $dossierpcg66_id
						),
						'contain' => array(
							'Personnepcg66' => array(
								'Statutpdo',
								'Situationpdo'
							),
                            'Decisiondefautinsertionep66' => array(
                                'Passagecommissionep'
                            )
						)
					)
				);
				$this->assert( !empty( $dossierpcg66 ), 'invalidParameter' );

				//Recherche des personnes liées au foyer

				$personnespcgs66 = $this->Dossierpcg66->Personnepcg66->find(
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

				$this->set( 'personnespcgs66', $personnespcgs66 );
				$this->set( 'dossierpcg66_id', $dossierpcg66_id );
				$foyer_id = $dossierpcg66['Dossierpcg66']['foyer_id'];

				$this->set( 'etatdossierpcg', $dossierpcg66['Dossierpcg66']['etatdossierpcg'] );

				//Gestion des décisions pour le dossier au niveau foyer
				$joins = array(
					array(
						'table'      => 'pdfs',
						'alias'      => 'Pdf',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Pdf.modele' => 'Decisiondossierpcg66',
							'Pdf.fk_value = Decisiondossierpcg66.id'
						)
					),
					array(
						'table'      => 'decisionspdos',
						'alias'      => 'Decisionpdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionpdo.id = Decisiondossierpcg66.decisionpdo_id'
						)
					),
				);

				$decisionsdossierspcgs66 = $this->{$this->modelClass}->Decisiondossierpcg66->find(
					'all',
					array(
						'fields' => array(
							'Decisiondossierpcg66.id',
							'Decisiondossierpcg66.dossierpcg66_id',
							'Decisiondossierpcg66.decisionpdo_id',
							'Decisiondossierpcg66.datepropositiontechnicien',
							'Decisiondossierpcg66.commentairetechnicien',
							'Decisiondossierpcg66.commentaire',
							'Decisiondossierpcg66.avistechnique',
							'Decisiondossierpcg66.commentaireavistechnique',
							'Decisiondossierpcg66.dateavistechnique',
							'Decisiondossierpcg66.etatdossierpcg',
							'Decisiondossierpcg66.validationproposition',
							'Decisiondossierpcg66.motifannulation',
							'Decisiondossierpcg66.commentairevalidation',
							'Decisiondossierpcg66.datevalidation',
							'Decisionpdo.libelle',
							'Pdf.fk_value',
                            $this->{$this->modelClass}->Decisiondossierpcg66->Fichiermodule->sqNbFichiersLies( $this->{$this->modelClass}->Decisiondossierpcg66, 'nb_fichiers_lies' )
						),
						'conditions' => array(
							'dossierpcg66_id' => $dossierpcg66_id
						),
		                'joins' => $joins,
						'order' => array(
							'Decisiondossierpcg66.modified DESC'
						),
						'recursive' => -1
					)
				);

				$this->set( compact( 'decisionsdossierspcgs66' ) );
				if ( !empty( $decisionsdossierspcgs66 ) ) {
					$lastDecisionId = $decisionsdossierspcgs66[0]['Decisiondossierpcg66']['id'];
					( is_numeric( $decisionsdossierspcgs66[0]['Decisiondossierpcg66']['validationproposition'] ) ) ? $ajoutDecision = true : $ajoutDecision = false;
				}
				else{
					$lastDecisionId = null;
					$ajoutDecision = null;
				}
				$this->set( compact( 'ajoutDecision', 'lastDecisionId' ) );
			}


			$personnesFoyer = $this->Dossierpcg66->Foyer->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Prestation.rolepers',
                        $this->Dossierpcg66->Foyer->Personne->sqVirtualField('nom_complet')
					),
					'conditions' => array(
						'Personne.foyer_id' => $foyer_id,
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'joins' => array(
						$this->Dossierpcg66->Foyer->Personne->join( 'Prestation' )
					),
					'contain' => false,
                    'order' => array( 'Prestation.rolepers DESC' )
				)
			);
            $personneDem = $personnesFoyer[0];
			$this->set( compact( 'personneDem' ) );


			//Gestion des jetons
			$dossier_id = $this->Dossierpcg66->Foyer->dossierId( $foyer_id );
			$this->Jetons2->get( $dossier_id );

			// Essai de sauvegarde
			if( !empty( $this->request->data ) ) {
				$this->Dossierpcg66->begin();

				$saved = $this->Dossierpcg66->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) );
				
				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers(
						$dir,
						!Set::classicExtract( $this->request->data, "Dossierpcg66.haspiecejointe" ),
						( ( $this->action == 'add' ) ? $this->Dossierpcg66->id : $id )
					) && $saved;
				}

                // Mise à jour des dossiers d'EP ayant généré un dossier PCG suite à une EP Audition
                // Uniquement si le dossie PCG est issu d'un EP Audition, que son decisiondefautinsertionep66_id is not null
                // et que le dossier est dans un état annulé, traité, décision validée ou transmis à un OP
//                if( $saved ) {
//                    if( isset( $dossierpcg66['Dossierpcg66']['decisiondefautinsertionep66_id'] ) && !empty( $dossierpcg66['Dossierpcg66']['decisiondefautinsertionep66_id'] ) ) {
//                        if( in_array( $dossierpcg66['Dossierpcg66']['etatdossierpcg'], array( 'annule', 'traite', 'decisionvalid', 'transmisop' ) ) ) {
//                            $saved = $this->Dossierpcg66->WebrsaDossierpcg66->updateEtatPassagecommissionep( $dossierpcg66['Dossierpcg66']['decisiondefautinsertionep66_id'] ) && $saved;
//                        }
//                    }
//                }
                if( $saved ) {
                    $etatdossierpcg = $dossierpcg66['Dossierpcg66']['etatdossierpcg'];
                    // Si nous sommes dans un état final du dossier PCG
                    if( in_array( $etatdossierpcg, array( 'annule', 'traite', 'decisionvalid', 'transmisop' ) ) ) {
                        if( isset( $dossierpcg66['Dossierpcg66']['decisiondefautinsertionep66_id'] ) && !empty( $dossierpcg66['Dossierpcg66']['decisiondefautinsertionep66_id'] ) ) {
                            //On regarde la liste des personnes du foyer
                            $personnesIds = Hash::extract( $personneDem, '{n}.Personne.id' );
                            //On regarde la liste des dossiers d'EPs restants pour la thématique defautsinsertionseps66
                            // pour ces personnes
                            $dossierEPEnCours = $this->Dossierpcg66->Personnepcg66->Personne->Dossierep->find(
                                'all',
                                $this->Dossierpcg66->Personnepcg66->Personne->Dossierep->qdDossiersepsOuverts( $personnesIds)
                            );

                            // Si des dossiers existent encore
                            if( isset($dossierEPEnCours) && !empty( $dossierEPEnCours ) ) {
                                // On récupère la liste des identifiants de ces dossiers d'EPs
                                $dossiersEpsIds = Hash::extract( $dossierEPEnCours, '{n}.Dossierep.id' );

                                // On récupère leur passage en commission respectif
                                $passagesParDossierseps = $this->Dossierpcg66->Personnepcg66->Personne->Dossierep->find(
                                    'all',
                                    array(
                                        'conditions' => array(
                                            'Dossierep.id' => $dossiersEpsIds,
                                            'Dossierep.themeep' => 'defautsinsertionseps66'
                                        ),
                                        'contain' => array('Passagecommissionep')
                                    )
                                );
                                $passagesIds = Hash::extract( $passagesParDossierseps, '{n}.Passagecommissionep.{n}.id' );

                                // Si des identifiants existent, on met à jour l'état du dossier ep en traité
                                if( isset( $passagesIds ) && !empty( $passagesIds ) ) {
                                    $this->Dossierpcg66->Decisiondefautinsertionep66->Passagecommissionep->updateAllUnBound(
                                        array( 'Passagecommissionep.etatdossierep' => '\'traite\'' ),
                                        array(
                                            '"Passagecommissionep"."dossierep_id"' => $dossiersEpsIds,
                                            '"Passagecommissionep"."id"' => $passagesIds
                                        )
                                    );
                                }
                            }

                            if( in_array( $dossierpcg66['Dossierpcg66']['etatdossierpcg'], array( 'annule', 'traite', 'decisionvalid', 'transmisop' ) ) ) {
                                $saved = $this->Dossierpcg66->WebrsaDossierpcg66->updateEtatPassagecommissionep( $dossierpcg66['Dossierpcg66']['decisiondefautinsertionep66_id'] ) && $saved;
                            }
                        }
                    }
                }
				
				if( $saved ) {
					$this->Dossierpcg66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array(  'controller' => 'dossierspcgs66','action' => 'index', $foyer_id ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Dossierpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			//Affichage des données
			elseif( $this->action == 'edit' ) {
				$this->request->data = $dossierpcg66;
			}

			if( $this->action == 'edit' ) {
				$fichiersEnBase = $this->Dossierpcg66->Fichiermodule->find(
					'all',
					array(
						'fields' => array(
							'Fichiermodule.id',
							'Fichiermodule.name',
							'Fichiermodule.fk_value',
							'Fichiermodule.modele',
							'Fichiermodule.cmspath',
							'Fichiermodule.mime',
							'Fichiermodule.created',
							'Fichiermodule.modified',
						),
						'conditions' => array(
							'Fichiermodule.modele' => 'Dossierpcg66',
							'Fichiermodule.fk_value' => $id,
						),
						'contain' => false
					)
				);
				$fichiersEnBase = Set::classicExtract( $fichiersEnBase, '{n}.Fichiermodule' );
				$this->set( 'fichiersEnBase', $fichiersEnBase );


                $this->request->data['Dossierpcg66']['user_id'] = $dossierpcg66['Dossierpcg66']['poledossierpcg66_id'].'_'.$dossierpcg66['Dossierpcg66']['user_id'];
			}



//debug($this->request->data);
			// avistechniquemodifiable, validationmodifiable
			$etatdossierpcg = '';
			if( isset( $dossierpcg66 ) ) {
				$etatdossierpcg = $dossierpcg66['Dossierpcg66']['etatdossierpcg'];
			}

            $gestionnairemodifiable = $personnedecisionmodifiable = false;

            // Récupération du gestionnaire précédent et remplissage de la liste déroulante avec cette valeur par défaut
            $dossierpcg66Pcd = $this->Dossierpcg66->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossierpcg66.foyer_id' => $foyer_id
                    ),
                    'recursive' => -1,
                    'order' => array( 'Dossierpcg66.created DESC'),
                    'limit' => 1
                )
            );

            if( !empty( $dossierpcg66Pcd ) && in_array( $etatdossierpcg, array( '', 'attaffect' ) ) ) {
                $this->request->data['Dossierpcg66']['poledossierpcg66_id'] = $dossierpcg66Pcd['Dossierpcg66']['poledossierpcg66_id'];
                $this->request->data['Dossierpcg66']['user_id'] = $dossierpcg66Pcd['Dossierpcg66']['poledossierpcg66_id'].'_'.$dossierpcg66Pcd['Dossierpcg66']['user_id'];
                $this->request->data['Dossierpcg66']['etatdossierpcg'] = 'attinstr';
                $gestionnairemodifiable = true;
            }

			switch( $etatdossierpcg ) {
				case '':
					$gestionnairemodifiable = $gestionnairemodifiable;
                    break;
				case 'attaffect':
					$gestionnairemodifiable = true;
					break;
				default:
					$gestionnairemodifiable = true;
					$personnedecisionmodifiable = true;
				break;
			}

			$this->set( compact( 'gestionnairemodifiable', 'personnedecisionmodifiable' ) );

			// Assignation à la vue
			$this->set( 'fichiers', $fichiers );
			$this->set( 'foyer_id', $foyer_id );

			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function view( $dossierpcg66_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Dossierpcg66->dossierId( $dossierpcg66_id ) ) ) );

			$dossierpcg66 = $this->WebrsaDossierpcg66->findDossierpcg($dossierpcg66_id);
			$this->assert( !empty( $dossierpcg66 ), 'invalidParameter' );

			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'index', $foyer_id ) );
			}

			$traitementsCourriersEnvoyes = array();
			if( Hash::get($dossierpcg66, 'Personnepcg66') ) {
				//Récupération de la liste des courriers envoyés à l'allocataire:
				$personnesIds = array();
				foreach( $dossierpcg66['Personnepcg66'] as $i => $personnepcg66 ) {
					$personnesIds[] = $personnepcg66['personne_id'];
				}
				$traitementsCourriersEnvoyes = $this->Dossierpcg66->WebrsaDossierpcg66->listeCourriersEnvoyes( $personnesIds, $dossierpcg66 );
			}

            // Liste des organismes auxquels on transmet le dossier
			$listOrgs = (array)Hash::extract( $dossierpcg66, 'Decisiondossierpcg66.0.Notificationdecisiondossierpcg66.{n}.name' );
			$orgs = implode( ', ',  $listOrgs );

			$this->_setOptions();
			$this->set( compact( 'dossierpcg66', 'orgs', 'datetransmissionop', 'foyer_id', 'traitementsCourriersEnvoyes' ) );
			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'id' => $this->Dossierpcg66->dossierId( $id ) ) );

			$dossierpcg66 = $this->Dossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $id
					),
					'contain' => false,
					'fields' => array(
						'Dossierpcg66.foyer_id'
					)
				)
			);

			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );

			$success = $this->Dossierpcg66->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'index', $foyer_id ) );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function cancel( $id ) {
			$qd_dossierpcg66 = array(
				'conditions' => array(
					'Dossierpcg66.id' => $id
				),
				'recursive' => -1
			);
			$dossierpcg66 = $this->Dossierpcg66->find( 'first', $qd_dossierpcg66 );

			$foyer_id = Hash::get( $dossierpcg66, 'Dossierpcg66.foyer_id' );
			$this->set( 'foyer_id', $foyer_id );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer_id ) ) );

			$dossier_id = $this->Dossierpcg66->dossierId( $id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $foyer_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Dossierpcg66->begin();

				$this->request->data['Dossierpcg66']['etatdossierpcg'] = 'annule';
				$saved = $this->Dossierpcg66->save( $this->request->data );

				if( $saved ) {
					$this->Dossierpcg66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $foyer_id ) );
				}
				else {
					$this->Dossierpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/erreur' );
				}
			}
			else {
				$this->request->data = $dossierpcg66;
			}
			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
		}
		
		/**
		 * Moteur de recherche
		 */
		public function search() {
			$this->helpers[] = 'Search.SearchForm';
			$Recherches = $this->Components->load( 'WebrsaRecherchesDossierspcgs66' );
			$Recherches->search();
			$this->Dossierpcg66->validate = array();
		}

		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesDossierspcgs66' );
			$Recherches->exportcsv( array( 'view' => 'exportcsv' ) );
		}
		
		/**
		 * Moteur de recherche
		 */
		public function search_gestionnaire() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesDossierspcgs66' );
			$Recherches->search();
			$this->Dossierpcg66->validate = array();
		}

		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_gestionnaire() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesDossierspcgs66' );
			$Recherches->exportcsv( array( 'view' => 'exportcsv' ) );
		}
		
		/**
		 * Moteur de recherche
		 */
		public function search_affectes() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesDossierspcgs66' );
			$Recherches->search();
			$this->Dossierpcg66->validate = array();
		}

		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_affectes() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesDossierspcgs66' );
			$Recherches->exportcsv();
		}

		/**
		 * Cohorte
		 */
		public function cohorte_enattenteaffectation() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$this->Dossierpcg66->validate = array(
				'poledossierpcg66_id' => array( 'notEmpty' => array( 'rule' => 'notEmpty' ) )
			);
			$this->Dossierpcg66->Typepdo->validate = array();
			$this->Dossierpcg66->Originepdo->validate = array();
			
			$Recherches->cohorte( array( 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Enattenteaffectation' ) );
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_enattenteaffectation() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$Recherches->exportcsv( array( 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Enattenteaffectation' ) );
		}

		/**
		 * Cohorte
		 */
		public function cohorte_atransmettre() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$this->Dossierpcg66->validate = array();
			$this->Dossierpcg66->Typepdo->validate = array();
			$this->Dossierpcg66->Originepdo->validate = array();
			$this->Dossierpcg66->Decisiondossierpcg66->Decdospcg66Orgdospcg66->validate = array(
				'orgtransmisdossierpcg66_id' => array( 'notEmpty' => array( 'rule' => 'notEmpty' ) )
			);
			
			$Recherches->cohorte( array( 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Atransmettre' ) );
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_atransmettre() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$Recherches->exportcsv( array( 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Atransmettre' ) );
		}
				
		/**
		 * Cohorte
		 */
		public function cohorte_heberge() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$Recherches->cohorte( array( 'modelName' => 'Dossier', 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Heberge' ) );
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_heberge() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$Recherches->exportcsv( array( 'modelName' => 'Dossier', 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Heberge' ) );
		}
				
		/**
		 * Cohorte
		 */
		public function cohorte_rsamajore() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$Recherches->cohorte( array( 'modelName' => 'Dossier', 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Rsamajore' ) );
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_rsamajore() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66' );
			$Recherches->exportcsv( array( 'modelName' => 'Dossier', 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Rsamajore' ) );
		}
		
		/**
		 * Cohorte
		 */
		public function cohorte_imprimer() {
			$Recherches = $this->Components->load( 'WebrsaCohortesDossierspcgs66Impressions' );
			$Recherches->search( array( 'modelName' => 'Dossierpcg66', 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Imprimer' ) );
		}
		
		/**
		 * Impression de la cohorte
		 */
		public function cohorte_imprimer_impressions() {
			$Cohortes = $this->Components->load( 'WebrsaCohortesDossierspcgs66Impressions' );
			$Cohortes->impressions( 
				array( 
					'modelName' => 'Dossierpcg66', 
					'modelRechercheName' => 'WebrsaCohorteDossierpcg66Imprimer',
					'configurableQueryFieldsKey' => 'Dossierspcgs66.cohorte_imprimer'
				) 
			);
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_imprimer() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesDossierspcgs66' );
			$Recherches->exportcsv( array( 'modelName' => 'Dossierpcg66', 'modelRechercheName' => 'WebrsaCohorteDossierpcg66Imprimer' ) );
		}

		/**
		 * Créer et envoi à l'utilisateur un fichier zip comprenant les Décisions valide d'un Dossier PCG
		 * et les traitements de type courrier à imprimer.
		 * 
		 * Remplace l'ancienne fonction : Decisionsdossierspcgs66::decisionproposition()
		 * qui envoyait un unique PDF de la proposition
		 * 
		 * NOTE : Un traitement doit avoir la valeur imprimer = 1 pour être imprimé (dans tout les cas)
		 * Un traitement ne sera imprimé que s'il est attaché à une proposition valide ou si il n'y a pas de proposition
		 * Dans la cohorte, ne sera affiché que les dossiers PCG avec une proposition validée 
		 * ou bien un traitement à imprimer sans proposition
		 * 
		 * Cas 1:	Dans le dossier pcg, la proposition est validée et un traitement est à imprimer.
		 *			Il faut imprimer la proposition et le traitement.
		 * 
		 * Cas 2:	Dans le dossier pcg, la proposition n'est pas validée et un traitement est à imprimer.
		 *			Il faut imprimer uniquement la proposition.
		 * 
		 * Cas 3:	Dans la cohorte, la proposition est validée et un traitement est à imprimer.
		 *			Il faut imprimer la proposition et le traitement.
		 * 
		 * Cas 4:	Dans la cohorte, il n'y a aucune proposition mais il y a un traitement à imprimer.
		 *			Il faut imprimer uniquement le traitement.
		 * 
		 * @param integer $id
		 * @param integer $decision_id decisiondossierpcg66 Appelé "proposition de décision"
		 */
		public function imprimer( $id, $decision_id = null ) {
			$this->assert( !empty( $id ), 'error404' );
			$this->DossiersMenus->checkDossierMenu( array( 'id' => $this->Dossierpcg66->dossierId( $id ) ) );
			
			$query = $this->Dossierpcg66->WebrsaDossierpcg66->getImpressionBaseQuery( $id );
			
			// Cas n° 1 et 2 : Dans dossier pcg, on précise $decision_id (pas dans la cohorte qui inclue les traitements sans proposition)
			// Note : Logiquement, il ne peut y avoir une proposition non validé 
			if ( $decision_id !== null ) {
				$query['conditions'][] = array( 'Decisiondossierpcg66.id' => $decision_id );
			}
			
			$results = $this->Dossierpcg66->find( 'first', $query );
			$decisionsdossierspcgs66_id = Hash::get($results, 'Decisiondossierpcg66.id');

			if ( !empty($results) ) {
				$success = true;

				$this->Dossierpcg66->Decisiondossierpcg66->begin();

				// Si l'etat du dossier est decisionvalid on le passe en atttransmiop avec une date d'impression
				if ( Hash::get( $results, 'Dossierpcg66.etatdossierpcg' ) === 'decisionvalid' ) {
					$results['Dossierpcg66']['dateimpression'] = date('Y-m-d');
					$results['Dossierpcg66']['etatdossierpcg'] = 'atttransmisop';
					$success = $this->Dossierpcg66->Decisiondossierpcg66->Dossierpcg66->save($results['Dossierpcg66']);
				}
				
				$decisionPdf = $decisionsdossierspcgs66_id !== null 
					? $this->Dossierpcg66->Decisiondossierpcg66->WebrsaDecisiondossierpcg66->getPdfDecision( $decisionsdossierspcgs66_id )
					: null
				;

				$courriers = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->WebrsaTraitementpcg66->getPdfsByDossierpcg66Id( $id, $this->Session->read('Auth.User.id') );
				$queryCourrier = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->WebrsaTraitementpcg66->getPdfsQuery($id);
				
				$traitementspcgs66_ids = Hash::extract($this->Dossierpcg66->Foyer->find('all', $queryCourrier), '{n}.Traitementpcg66.id');
				
				if ($success && !empty($traitementspcgs66_ids)) {
					$success = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->updateAllUnbound(
						array( 'etattraitementpcg' => "'attente'" ),
						array( 'id' => $traitementspcgs66_ids )
					);
				}
				
				if( $success && ( $decisionPdf !== null || !empty($courriers) ) ) {
					
					$this->Dossierpcg66->Decisiondossierpcg66->commit();

					$prefix = 'Dossier_PCG';
					$date = date('Y-m-d');
					$allocatairePrincipal = Hash::get( $results, 'Personne.nom' ) . '_' . Hash::get( $results, 'Personne.prenom' );
					$fileName = "{$date}_{$prefix}_{$id}_Courrier_{$allocatairePrincipal}.pdf";
					$PdfUtility = new WebrsaPdfUtility();
					$pdfList = array();
					
					if ( $decisionPdf !== null ) {
						$pdfList[] = $decisionPdf;
						$fileName = "{$date}_{$prefix}_{$id}_Decision_{$allocatairePrincipal}.pdf";
					}
					
					foreach ( $courriers as $i => $courrier ) {
						$pdf = $courrier;
						$pdfList[] = $pdf;
					}

					if ( Configure::read('Dossierspcgs66.imprimer_cohorte.Impression.RectoVerso') ) {
						$pdfList = $PdfUtility->preparePdfListForRectoVerso( $pdfList );
					}
					
					$concatPdf = $this->Gedooo->concatPdfs($pdfList, 'Dossierpcg66');
					$this->Gedooo->sendPdfContentToClient($concatPdf, $fileName);
				}
				else {
					$this->Dossierpcg66->Decisiondossierpcg66->rollback();
				}
				
			}

			$this->Session->setFlash( 'Impossible de générer le(s) fichier PDF', 'default', array( 'class' => 'error' ) );
			$this->redirect( $this->referer() );
		}
		
		public function ajax_view_decisions($dossierpcg66_id) {
			$decisionsdossierspcgs66 = $this->Dossierpcg66->find('all',
				array(
					'fields' => array_merge(
						$this->Dossierpcg66->Decisiondossierpcg66->fields(),
						array(
							'Dossierpcg66.id',
							'Dossierpcg66.foyer_id',
							'Decisionpdo.libelle'
						)
					),
					'contain' => false,
					'joins' => array(
						$this->Dossierpcg66->join('Decisiondossierpcg66'),
						$this->Dossierpcg66->Decisiondossierpcg66->join('Decisionpdo'),
					),
					'conditions' => array('Dossierpcg66.id' => $dossierpcg66_id)
				)
			);
			
			$users = array();
			$users_list = $this->Dossierpcg66->Decisiondossierpcg66->User->find('all', 
				array('fields' => array('id', 'nom', 'prenom'), 'contain' => false)
			);
			foreach ($users_list as $user) {
				$users[$user['User']['id']] = $user['User']['nom'].' '.$user['User']['prenom'];
			}
			
			$this->set(compact('decisionsdossierspcgs66', 'dossierMenu', 'users'));
			
			$this->render('ajax_view_decisions', 'ajax');
		}
	}
?>