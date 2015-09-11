<?php
	/**
	 * Code source de la classe OrientsstructsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');
	App::uses( 'DepartementUtility', 'Utility' );

	/**
	 * La classe OrientsstructsController ...
	 *
	 * @package app.Controller
	 */
	class OrientsstructsController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Orientsstructs';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Cohortes',
			'DossiersMenus',
			'Fileuploader',
			'Gedooo.Gedooo',
			'InsertionsAllocataires',
			'Jetons2',
			'Search.Filtresdefaut' => array(
				'cohorte_nouvelles',
				'search'
			),
			'Search.SearchPrg' => array(
				'actions' => array(
					'search' => array( 'filter' => 'Search' )
				)
			)
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Allocataires',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
			'Fileuploader'
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Orientstruct', 'WebrsaOrientstruct'/*, 'Option'*/ );

		/**
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'ajaxfileupload' => 'Orientsstructs:filelink',
			'ajaxfiledelete' => 'Orientsstructs:filelink',
			'download' => 'Orientsstructs:filelink',
			'fileview' => 'Orientsstructs:filelink',
			'search' => 'Criteres:index',
			'exportcsv' => 'Criteres:exportcsv',
			'cohorte_nouvelles' => 'Cohortes:nouvelles',
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxfileupload' => 'create',
			'ajaxfiledelete' => 'delete',
			'cohorte_nouvelles' => 'update',
			'fileview' => 'read',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'filelink' => 'read',
			'index' => 'read',
			'impression' => 'read',
			'impression_changement_referent' => 'read',
			'search' => 'read',
			'exportcsv' => 'read'
		);

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
		 * @param integer $id
		 */
		public function filelink( $id ) {
			// Début TODO: à mettre en commun
			$personne_id = $this->Orientstruct->personneId( $id );
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->set( compact( 'dossierMenu' ) );

			$this->Fileuploader->filelink( $id, array( 'action' => 'index', $personne_id ) );
			$this->set( 'urlmenu', "/orientsstructs/index/{$personne_id}" );

			$options = $this->Orientstruct->enums();
			$this->set( compact( 'options' ) );
		}

		/**
		 * Permet de compléter le tableau de résultats généré par
		 * Orientstruct::getIndexQuery(), pour les droits (edit, delete) et le
		 * "Rang d'orientation".
		 *
		 * @param array $results
		 * @param array $params
		 * @return array
		 */
		protected function _getCompletedIndexResults( array $results, array $params = array() ) {
			$departement = Configure::read( 'Cg.departement' );

			$rgsorients = Hash::extract( $results, "{n}.Orientstruct[statut_orient=Orienté].rgorient" );
			$rgorientMax = 0;
			if( !empty( $rgsorients ) ) {
				$rgorientMax = max( $rgsorients );
			}

			foreach( array_keys( $results ) as $key ) {
				// On ne peut modifier que l'entrée la plus récente
				$results[$key]['Orientstruct']['edit'] = ( $key == 0 ) && $params['ajout_possible'];

				// On ne peut modifier que l'entrée la plus récente
				$results[$key]['Orientstruct']['impression'] = ( $results[$key]['Orientstruct']['printable'] == 1 );

				// On ne peut supprimer que l'entrée la plus récente
				$results[$key]['Orientstruct']['delete'] = ( $key == 0 ) && ( $results[$key]['Orientstruct']['rgorient'] == $rgorientMax ) && !$results[$key]['Orientstruct']['linked_records'] && empty( $params['reorientationseps'] );

				if( $departement == 66 ) {
					// On ne peut modifier que la dernière orientation, celle dont le rang est le plus élevé
					$results[$key]['Orientstruct']['edit'] = ( $results[$key]['Orientstruct']['rgorient'] == $rgorientMax ) && $results[$key]['Orientstruct']['edit'];

					//Peut-on imprimer la notif de changement de référent ou non, si 1ère orientation non sinon ok
					$results[$key]['Orientstruct']['impression_changement_referent'] = ( $results[$key]['Orientstruct']['rgorient'] > 1 ) && $results[$key]['Orientstruct']['notifbenefcliquable'];

					// Délai de modification orientation (10 jours par défaut)
					$date_valid = Hash::get( $results[$key], "Orientstruct.date_valid" );
					$periodeblock = !empty( $date_valid ) && ( time() >= ( strtotime( $date_valid ) + 3600 * Configure::read( 'Periode.modifiableorientation.nbheure' ) ) );
					$results[$key]['Orientstruct']['edit'] = !$periodeblock && $results[$key]['Orientstruct']['edit'];
				}

				// Le "rang d'orientation"
				if( !empty( $results[$key]['Orientstruct']['rgorient'] ) ) {
					if( $departement == 58 ) {
						if( !isset( $results[$key+1] ) ) {
							$rgorient = 'Première orientation';
						}
						else if( $results[$key]['Orientstruct']['typeorient_id'] != $results[$key+1]['Orientstruct']['typeorient_id'] ) {
							$rgorient = 'Réorientation';
						}
						else if( $results[$key]['Orientstruct']['typeorient_id'] == Configure::read( 'Typeorient.emploi_id' ) ) {
							$rgorient = 'Maintien en emploi';
						}
						else {
							$rgorient = 'Maintien en social';
						}

						$results[$key]['Orientstruct']['rgorient'] = $rgorient;
					}
					elseif ( $departement == 66 ){
						$results[$key]['Orientstruct']['rgorient'] = DepartementUtility::getTypeorientName($results, $key);
					}
					else {
						$results[$key]['Orientstruct']['rgorient'] = ( $results[$key]['Orientstruct']['rgorient'] == 1 ? 'Première orientation' : 'Réorientation' );
					}
				}
			}

			return $results;
		}

		/**
		 * Complète la liste des dossiers devant passer en COV en ajoutant des
		 * champs virtuels permettant de faire les liens dans la vue.
		 *
		 * @param array $results
		 * @param array $params
		 * @return array
		 */
		protected function _getCompletedIndexResultsReorientationscovs( array $results, array $params = array() ) {
			foreach( array_keys( $results ) as $key ) {
				$themecov58 = $results[$key]['Dossiercov58']['themecov58'];

				$results[$key]['Orientstruct']['rgorient'] = ( $params['rgorient_max'] + 1 );

				// Actions
				$results[$key]['Actions'] = array();

				// view
				$results[$key]['Actions']['view_url'] = "/Covs58/view/{$results[$key]['Cov58']['id']}#dossiers,".Inflector::singularize( $themecov58 );
				$results[$key]['Actions']['view_enabled'] = !empty( $results[$key]['Cov58']['id'] ) && WebrsaPermissions::check( 'covs58', 'view' );

				if( $themecov58 === 'proposorientationscovs58' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Proposorientationscovs58/edit/{$results[$key]['Personne']['id']}";
					$results[$key]['Actions']['edit_enabled'] = ( $results[$key]['Passagecov58']['etatdossiercov'] != 'associe' ) && WebrsaPermissions::checkDossier( 'proposorientationscovs58', 'add', $params['dossier_menu'] );

					// delete
					$results[$key]['Actions']['delete_url'] = "/Proposorientationscovs58/delete/{$results[$key]['Propoorientationcov58']['id']}";
					$results[$key]['Actions']['delete_enabled'] = empty( $results[$key]['Passagecov58']['id'] ) && WebrsaPermissions::checkDossier( 'proposorientationscovs58', 'delete', $params['dossier_menu'] );
				}
				else if( $themecov58 === 'proposorientssocialescovs58' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Proposorientssocialescovs58/edit/{$results[$key]['Propoorientsocialecov58']['id']}";
					$results[$key]['Actions']['edit_enabled'] = false;

					// delete
					$results[$key]['Actions']['delete_url'] = "/Proposorientssocialescovs58/delete/{$results[$key]['Propoorientsocialecov58']['id']}";
					$results[$key]['Actions']['delete_enabled'] = false;
				}
				else if( $themecov58 === 'proposnonorientationsproscovs58' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Proposnonorientationsproscovs58/edit/{$results[$key]['Propononorientationprocov58']['id']}";
					$results[$key]['Actions']['edit_enabled'] = false;

					// delete
					$results[$key]['Actions']['delete_url'] = "/Proposnonorientationsproscovs58/delete/{$results[$key]['Propononorientationprocov58']['id']}";
					$results[$key]['Actions']['delete_enabled'] = false;
				}
			}

			return $results;
		}

		/**
		 * Complète la liste des dossiers devant passer en EP en ajoutant des
		 * champs virtuels permettant notamment de faire les liens dans la vue.
		 *
		 * @param array $results
		 * @param array $params
		 * @return array
		 */
		protected function _getCompletedIndexResultsReorientationseps( array $results, array $params = array() ) {
			foreach( array_keys( $results ) as $key ) {
				$themeep = $results[$key]['Dossierep']['themeep'];

				$results[$key]['Orientstruct']['rgorient'] = ( $params['rgorient_max'] + 1 );

				// Actions
				$results[$key]['Actions'] = array();

				// view
				$results[$key]['Actions']['view_url'] = "/Commissionseps/view/{$results[$key]['Commissionep']['id']}#dossiers,".Inflector::singularize( $themeep );
				$results[$key]['Actions']['view_enabled'] = !empty( $results[$key]['Commissionep']['id'] ) && WebrsaPermissions::check( 'Commissionseps', 'view' );

				// CG 58
				if( $themeep === 'nonorientationsproseps58' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Nonorientationsproseps58/edit/{$results[$key]['Nonorientationproep58']['id']}";
					$results[$key]['Actions']['edit_enabled'] = false;

					// delete
					$results[$key]['Actions']['delete_url'] = "/Nonorientationsproseps58/delete/{$results[$key]['Nonorientationproep58']['id']}";
					$results[$key]['Actions']['delete_enabled'] = false;
				}
				else if( $themeep === 'regressionsorientationseps58' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Regressionorientationep58/edit/{$results[$key]['Regressionorientationep58']['id']}";
					$results[$key]['Actions']['edit_enabled'] = false;

					// delete
					$results[$key]['Actions']['delete_url'] = "/Regressionorientationep58/delete/{$results[$key]['Regressionorientationep58']['id']}";
					$results[$key]['Actions']['delete_enabled'] = empty( $results[$key]['Passagecommissionep']['id'] ) && WebrsaPermissions::checkDossier( 'regressionsorientationseps58', 'delete', $params['dossier_menu'] );
				}
				// CG 66
				else if( $themeep === 'saisinesbilansparcourseps66' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Saisinesbilansparcourseps66/edit/{$results[$key]['Saisinebilanparcoursep66']['id']}";
					$results[$key]['Actions']['edit_enabled'] = false;

					// delete
					$results[$key]['Actions']['delete_url'] = "/Saisinesbilansparcourseps66/delete/{$results[$key]['Saisinebilanparcoursep66']['id']}";
					$results[$key]['Actions']['delete_enabled'] = false;
				}
				// CG 93
				else if( $themeep === 'reorientationseps93' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Reorientationseps93/edit/{$results[$key]['Reorientationep93']['id']}";
					$results[$key]['Actions']['edit_enabled'] = empty( $results[$key]['Passagecommissionep']['id'] ) && WebrsaPermissions::checkDossier( 'reorientationseps93', 'edit', $params['dossier_menu'] );

					// delete
					$results[$key]['Actions']['delete_url'] = "/Reorientationseps93/delete/{$results[$key]['Reorientationep93']['id']}";
					$results[$key]['Actions']['delete_enabled'] = empty( $results[$key]['Passagecommissionep']['id'] ) && WebrsaPermissions::checkDossier( 'reorientationseps93', 'delete', $params['dossier_menu'] );
				}
				else if( $themeep === 'nonorientationsproseps93' ) {
					// edit
					$results[$key]['Actions']['edit_url'] = "/Nonorientationsproseps93/edit/{$results[$key]['Nonorientationproep93']['id']}";
					$results[$key]['Actions']['edit_enabled'] = false;

					// delete
					$results[$key]['Actions']['delete_url'] = "/Nonorientationsproseps93/delete/{$results[$key]['Nonorientationproep93']['id']}";
					$results[$key]['Actions']['delete_enabled'] = false;
				}
			}

			return $results;
		}

		/**
		 * Retourne la liste des actions de l'écran d'index, en fonction du CG,
		 * des orientations de l'allocataire et d'autres données passées en
		 * paramètres.
		 *
		 * @param array $records
		 * @param array $params
		 * @return array
		 */
		protected function _getIndexActionsList( array $records, array $params = array() ) {
			App::uses( 'DefaultUrl', 'Default.Utility' );
			App::uses( 'DefaultUtility', 'Default.Utility' );

			$departement = Configure::read( 'Cg.departement' );
			$domain = $this->request->params['controller'];
			$actions = array();

			if( $departement == 93 ) {
				if( $params['rgorient_max'] >= 1 ) {
					$actions["/Reorientationseps93/add/{$records[0]['Orientstruct']['id']}"] = array(
						'domain' => $domain,
						'enabled' => $params['ajout_possible'] && WebrsaPermissions::checkDossier( 'reorientationseps93', 'add', $params['dossier_menu'] )
					);
				}
				else {
					$actions["/Orientsstructs/add/{$params['personne_id']}"] = array(
						'domain' => $domain,
						'msgid' => 'Demander une réorientation',
						'enabled' => !$params['force_edit'] && $params['ajout_possible'] && WebrsaPermissions::checkDossier( 'orientsstructs', 'add', $params['dossier_menu'] )
					);
				}
			}
			else if( $departement == 58 ) {
				$actions["/Proposorientationscovs58/add/{$params['personne_id']}"] = array(
					'domain' => $domain,
					//'msgid' => 'Préconiser une orientation',
					'enabled' => $params['ajout_possible'] && WebrsaPermissions::checkDossier( 'proposorientationscovs58', 'add', $params['dossier_menu'] )
				);
			}
			else {
				$actions["/Orientsstructs/add/{$params['personne_id']}"] = array(
					'domain' => $domain,
					'msgid' => 'Ajouter',
					'enabled' => !$params['force_edit'] && $params['ajout_possible'] && WebrsaPermissions::checkDossier( 'orientsstructs', 'add', $params['dossier_menu'] )
				);
			}

			return $actions;
		}

		/**
		 * Liste des orientations d'une personne.
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id ) {
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->set( compact( 'dossierMenu' ) );

			$this->_setEntriesAncienDossier( $personne_id, 'Orientstruct' );
			//------------------------------------------------------------------
			$departement = Configure::read( 'Cg.departement' );

			$rgorient_max = $this->Orientstruct->rgorientMax( $personne_id );

			// Dossiers d'EP en cours de passage et pouvant déboucher sur une réorientation
			$reorientationseps = $this->Orientstruct->Personne->Dossierep->getReorientationsEnCours( $personne_id );
			$reorientationseps = $this->_getCompletedIndexResultsReorientationseps(
				$reorientationseps,
				array(
					'dossier_menu' => $dossierMenu,
					'rgorient_max' => $rgorient_max
				)
			);

			// Dossiers de COV en cours de passage et pouvant déboucher sur une réorientation
			$reorientationscovs = $this->Orientstruct->Personne->Dossiercov58->getReorientationsEnCours( $personne_id );
			$reorientationscovs = $this->_getCompletedIndexResultsReorientationscovs(
				$reorientationscovs,
				array(
					'dossier_menu' => $dossierMenu,
					'rgorient_max' => $rgorient_max
				)
			);

			// Droits sur les actions
			$ajoutPossible = $this->WebrsaOrientstruct->ajoutPossible( $personne_id )
					&& empty( $reorientationseps )
					&& empty( $reorientationscovs );

			$en_procedure_relance = $this->WebrsaOrientstruct->enProcedureRelance( $personne_id );

			$force_edit = ( $departement == 93 && $rgorient_max == 0 );

			// Liste des orientations
			$query = $this->WebrsaOrientstruct->getIndexQuery( $personne_id );
			$orientsstructs = $this->Orientstruct->find( 'all', $query );
			$orientsstructs = $this->_getCompletedIndexResults(
				$orientsstructs,
				array(
					'ajout_possible' => $ajoutPossible,
					'reorientationseps' => $reorientationseps
				)
			);

			// Options
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Commissionep' => array(
					'etatcommissionep' => $this->Orientstruct->Personne->Dossierep->Passagecommissionep->Commissionep->enum( 'etatcommissionep' )
				),
				'Cov58' => array(
					'etatcov' => $this->Orientstruct->Personne->Dossiercov58->Passagecov58->Cov58->enum( 'etatcov' )
				),
				'Dossiercov58' => array(
					'themecov58' => $this->Orientstruct->Personne->Dossiercov58->enum( 'themecov58' )
				),
				'Dossierep' => array(
					'themeep' => $this->Orientstruct->Personne->Dossierep->enum( 'themeep' )
				),
				'Orientstruct' => array(
					'statut_orient' => $this->Orientstruct->enum( 'statut_orient' )
				),
				'Passagecommissionep' => array(
					'etatdossierep' => $this->Orientstruct->Personne->Dossierep->Passagecommissionep->enum( 'etatdossierep' )
				),
				'Passagecov58' => array(
					'etatdossiercov' => $this->Orientstruct->Personne->Dossiercov58->Passagecov58->enum( 'etatdossiercov' )
				)
			);
			$options = Hash::merge( $options, $this->Orientstruct->enums() );

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$options['Orientstruct']['propo_algo'] = $this->InsertionsAllocataires->typesorients();
			}

			// Liste des actions accessibles
			$actions = $this->_getIndexActionsList(
				$orientsstructs,
				array(
					'dossier_menu' => $dossierMenu,
					'personne_id' => $personne_id,
					'ajout_possible' => $ajoutPossible,
					'force_edit' => $force_edit,
					'rgorient_max' => $rgorient_max,
				)
			);

			$this->set( compact( 'orientsstructs', 'reorientationseps', 'reorientationscovs', 'ajoutPossible', 'options', 'actions', 'en_procedure_relance' ) );
			$this->set( 'urlmenu', "/orientsstructs/index/{$personne_id}" );
		}

		/**
		 * Formulaire d'ajout d'une orientation.
		 *
		 * @see OrientsstructsController::edit()
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Formulaire de modification d'une orientation.
		 *
		 * @todo: permissions, voir dans la vue pour tous les CG
		 *
		 * @throws NotFoundException
		 */
		public function edit( $id = null ) {
			$personne_id = ( $this->action === 'add' ? $id : $this->Orientstruct->personneId( $id ) );
			$id = ( $this->action === 'add' ? null : $id );
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->set( compact( 'dossierMenu' ) );

			$this->Jetons2->get( Hash::get( $dossierMenu, 'Dossier.id' ) );

			// -----------------------------------------------------------------
			$redirectUrl = array( 'action' => 'index', $personne_id );
			$user_id = $this->Session->read( 'Auth.User.id' );
			// -----------------------------------------------------------------
			// Retour à l'index s'il n'est pas possible d'ajouter une orientation
			if( $this->action === 'add' && !$this->WebrsaOrientstruct->ajoutPossible( $personne_id ) ) {
				$this->Session->setFlash( 'Impossible d\'ajouter une orientation à cette personne.', 'flash/error' );
				$this->redirect( $redirectUrl );
			}

			// -----------------------------------------------------------------
			//$originalAddEditFormData = $this->Orientstruct->getAddEditFormData( $personne_id, $id, $user_id );
			$originalAddEditFormData = $this->WebrsaOrientstruct->getAddEditFormData( $personne_id, $id, $user_id );

			// Retour à l'index si on essaie de modifier une autre orientation que la dernière
			if( $this->action === 'edit' && !empty( $originalAddEditFormData['Orientstruct']['date_valid'] ) && $originalAddEditFormData['Orientstruct']['statut_orient'] == 'Orienté' && $originalAddEditFormData['Orientstruct']['rgorient'] != $this->Orientstruct->rgorientMax( $originalAddEditFormData['Orientstruct']['personne_id'] ) ) {
				$this->Session->setFlash( 'Impossible de modifier une autre orientation que la plus récente.', 'flash/error' );
				$this->redirect( $redirectUrl );
			}
			// -----------------------------------------------------------------
			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( Hash::get( $dossierMenu, 'Dossier.id' ) );
				$this->redirect( $redirectUrl );
			}
			// -----------------------------------------------------------------

			$user_id = $this->Session->read( 'Auth.User.id' );

			// Tentative de sauvegarde
			if( !empty( $this->request->data ) ) {
				$this->Orientstruct->begin();
//				if( $this->Orientstruct->saveAddEditFormData( $this->request->data, $user_id ) ) {
				if( $this->WebrsaOrientstruct->saveAddEditFormData( $this->request->data, $user_id ) ) {
					$this->Orientstruct->commit();
					$this->Jetons2->release( Hash::get( $dossierMenu, 'Dossier.id' ) );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $redirectUrl );
				}
				else {
					$this->Orientstruct->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			// Remplissage du formulaire
			else {
				$this->request->data = $originalAddEditFormData;
			}

			// Options
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => $Option->toppersdrodevorsa()
				),
				'Orientstruct' => array(
					'typeorient_id' => $this->Orientstruct->Typeorient->listOptions(), // FIXME
					//'typeorient_id' => $this->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O' ) ) ),
					'structurereferente_id' => $this->InsertionsAllocataires->structuresreferentes( array( 'conditions' => array( 'Structurereferente.orientation' => 'O', 'Structurereferente.actif' => 'O' ) ) ),
					'referent_id' => $this->InsertionsAllocataires->referents( array( 'prefix' => true, 'conditions' => array( 'Referent.actif' => 'O' ) ) ),
					'statut_orient' => $this->Orientstruct->enum( 'statut_orient' ),
					// Pour le 66
					// -> FIXME ?
					'structureorientante_id' => $this->InsertionsAllocataires->structuresreferentes( array( 'list' => true, 'conditions' => array( 'Structurereferente.orientation' => 'O' ) ) ),
					'referentorientant_id' => $this->InsertionsAllocataires->referents( array( 'prefix' => true, 'conditions' => array( 'Referent.actif' => 'O' ) ) ),
				)
			);
			$options = Hash::merge( $options, $this->Orientstruct->enums() );
			$this->set( compact( 'options' ) );

			// Rendu
			$this->set( 'urlmenu', "/orientsstructs/index/{$personne_id}" );
			$this->render( 'edit' );
		}

		/**
		 * Suppression d'une orientation et redirection vers l'index.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$personne_id = $this->Orientstruct->personneId( $id );
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$dossier_id = Hash::get( $dossierMenu, 'Dossier.id' );

			$this->Jetons2->get( $dossier_id );

			$this->Orientstruct->begin();
			if( $this->Orientstruct->delete( $id ) ) {
				$this->Orientstruct->commit();
				$this->Jetons2->release( $dossier_id );
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Orientstruct->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}

		/**
		 * Impression d'une orientation.
		 *
		 * Méthode appelée depuis les vues:
		 * 	- cohortes/orientees
		 * 	- orientsstructs/index
		 *
		 * @param integer $id La clé primaire de l'Orientstruct
		 */
		public function impression( $id ) {
			$personne_id = $this->Orientstruct->personneId( $id );
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			if( in_array( Configure::read( 'Cg.departement' ), array( 66, 976 ) ) ) {
				$pdf = $this->Orientstruct->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );
			}
			else {
				$pdf = $this->Orientstruct->getStoredPdf( $id, 'date_impression' );
				$pdf = ( isset( $pdf['Pdf']['document'] ) ? $pdf['Pdf']['document'] : null );
			}

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'orientstruct_%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression de l\'orientation.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Impression d'une orientation lors d'un changement de référent.
		 *
		 * Méthode appelée depuis les vues:
		 * 	- cohortes/orientees
		 * 	- orientsstructs/index
		 *
		 * @param integer $id L'id de l'orientstruct que l'on souhaite imprimer.
		 * @return void
		 */
		public function impression_changement_referent( $id = null ) {
			$personne_id = $this->Orientstruct->personneId( $id );
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$pdf = $this->WebrsaOrientstruct->getChangementReferentOrientation( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'Notification_Changement_Referent_%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la notification.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Moteur de recherche par orientation (nouveau).
		 */
		public function search() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesOrientsstructs' );
			$Recherches->search();
		}

		/**
		 * Export du tableau de résultats de la recherche par orientation (nouveau).
		 */
		public function exportcsv() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesOrientsstructs' );
			$Recherches->exportcsv();
		}

		/**
		 * Cohorte des demandes non orientées (nouveau)
		 */
		public function cohorte_nouvelles() {
			$Gedooo = $this->Components->load( 'Gedooo.Gedooo' );
			$this->Gedooo->check( false, true );

			$this->loadModel( 'Personne' );
			$Cohortes = $this->Components->load( 'WebrsaCohortesOrientsstructsNouvelles' );

			$Cohortes->cohorte(
				array(
					'modelName' => 'Personne',
					'modelRechercheName' => 'WebrsaCohorteOrientstructNouvelle'
				)
			);
		}
	}
?>
