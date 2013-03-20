<?php
    /**
	 * Code source de la classe Apres66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'CakeEmail', 'Network/Email' );

	/**
	 * La classe Apres66Controller permet de lister, voir, ajouter, supprimer, ...  des APREs (CG 66).
	 *
	 * @package app.Controller
	 */
	class Apres66Controller extends AppController
	{
		public $name = 'Apres66';

		public $uses = array( 'Apre66', 'Aideapre66', 'Pieceaide66', 'Typeaideapre66', 'Themeapre66', 'Option', 'Personne', 'Prestation', 'Pieceaide66Typeaideapre66', 'Adressefoyer', 'Fraisdeplacement66', 'Structurereferente', 'Referent', 'Piececomptable66Typeaideapre66', 'Piececomptable66', 'Foyer' );

		public $helpers = array( 'Default', 'Locale', 'Cake1xLegacy.Ajax', 'Xform', 'Xhtml', 'Fileuploader', 'Default2' );

		public $components = array( 'Default', 'Gedooo.Gedooo', 'Fileuploader', 'Jetons2', 'DossiersMenus' );

		public $commeDroit = array(
			'view66' => 'Apres66:index',
			'add' => 'Apres66:edit'
		);

		public $aucunDroit = array( 'ajaxstruct', 'ajaxref', 'ajaxtierspresta', 'ajaxtiersprestaformqualif', 'ajaxtiersprestaformpermfimo', 'ajaxtiersprestaactprof', 'ajaxtiersprestapermisb', 'ajaxpiece', 'notificationsop', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

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
			'ajaxpiece' => 'read',
			'ajaxref' => 'read',
			'ajaxstruct' => 'read',
			'cancel' => 'update',
			'download' => 'read',
			'edit' => 'update',
			'filelink' => 'read',
			'fileview' => 'read',
			'impression' => 'read',
			'index' => 'read',
			'indexparams' => 'read',
			'maillink' => 'read',
			'notifications' => 'read',
			'view66' => 'read',
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$options = $this->{$this->modelClass}->allEnumLists();

			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'typeservice', ClassRegistry::init( 'Serviceinstructeur' )->find( 'first' ) );

			$this->set( 'themes', $this->Themeapre66->find( 'list' ) );
			$this->set( 'nomsTypeaide', $this->Typeaideapre66->find( 'list' ) );

			$options = Set::merge( $options, $this->{$this->modelClass}->Aideapre66->allEnumLists() );

			$this->set( 'options', $options );
			$pieceadmin = $this->Pieceaide66->find(
					'list', array(
				'fields' => array(
					'Pieceaide66.id',
					'Pieceaide66.name'
				),
				'contain' => false
					)
			);
			$this->set( 'pieceadmin', $pieceadmin );
			$piececomptable = $this->Piececomptable66->find(
					'list', array(
				'fields' => array(
					'Piececomptable66.id',
					'Piececomptable66.name'
				),
				'contain' => false
					)
			);
			$this->set( 'piececomptable', $piececomptable );
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
		 * Téléchargement des fichiers préalablement associés à un traitement donné
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		 * Fonction permettant d'accéder à la page pour lier les fichiers.
		 *
		 * @param integer $id
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->{$this->modelClass}->personneId( $id ) ) ) );

			$fichiers = array( );
			$apre = $this->{$this->modelClass}->find(
					'first', array(
				'conditions' => array(
					"{$this->modelClass}.id" => $id
				),
				'contain' => array(
					'Fichiermodule' => array(
						'fields' => array( 'name', 'id', 'created', 'modified' )
					)
				)
					)
			);

			$personne_id = $apre[$this->modelClass]['personne_id'];
			$dossier_id = $this->{$this->modelClass}->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
                $this->Jetons2->release( $dossier_id );
				$redirect_url = $this->Session->read( "Savedfilters.{$this->name}.{$this->action}" );
				if( !empty( $redirect_url ) ) {
					$this->Session->delete( "Savedfilters.{$this->name}.{$this->action}" );
					$this->redirect( $redirect_url );
				}
				else {
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
			}

			if( !empty( $this->request->data ) ) {
                $this->{$this->modelClass}->begin();

				$saved = $this->{$this->modelClass}->updateAllUnBound(
						array( "{$this->modelClass}.haspiecejointe" => '\''.$this->request->data[$this->modelClass]['haspiecejointe'].'\'' ), array(
					"{$this->modelClass}.personne_id" => $personne_id,
					"{$this->modelClass}.id" => $id
						)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "{$this->modelClass}.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->{$this->modelClass}->commit();
                    $this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'apre' ) );
			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'filelink' );
		}

		/**
		 * Permet de regrouper l'ensemble des paramétrages pour l'APRE
		 */
		public function indexparams() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$compteurs = array(
				'Pieceaide66' => ClassRegistry::init( 'Pieceaide66' )->find( 'count' ),
				'Themeapre66' => ClassRegistry::init( 'Themeapre66' )->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );

			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'indexparams_'.Configure::read( 'nom_form_apre_cg' ) );
		}

		/**
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$personne = $this->{$this->modelClass}->Personne->find( 'first', $qd_personne );
			$this->assert( !empty( $personne ), 'invalidParameter' );
			$this->set( 'personne', $personne );

			$apres = $this->{$this->modelClass}->find(
					'all', array(
				'conditions' => array(
					"{$this->modelClass}.personne_id" => $personne_id
				)
					)
			);
			$this->set( 'apres', $apres );
// debug($apres);
			$referents = $this->Referent->find( 'list' );
			$this->set( 'referents', $referents );

			$this->set( 'personne_id', $personne_id );


			/// La personne a-t'elle bénéficié d'aides trop importantes ?
			$alerteMontantAides = false;
			$montantMaxComplementaires = Configure::read( 'Apre.montantMaxComplementaires' );
			$periodeMontantMaxComplementaires = Configure::read( 'Apre.periodeMontantMaxComplementaires' );

			$year = date( 'Y' );
			$yearMax = $year + Configure::read( 'Apre.periodeMontantMaxComplementaires' ) - 1;

			$apresPourCalculMontant = $this->{$this->modelClass}->find(
					'all', array(
				'conditions' => array(
					"{$this->modelClass}.personne_id" => $personne_id,
					"{$this->modelClass}.statutapre" => 'C',
					"Aideapre66.datemontantpropose BETWEEN '{$year}-01-01' AND '{$yearMax}-12-31'",
// 						"Aideapre66.datedemande >=" => date( 'Y-m-d', strtotime( '-'.Configure::read( "Apre.periodeMontantMaxComplementaires" ).' months' ) )
				),
				'contain' => array(
					'Personne',
					'Aideapre66'
				)
					)
			);

			$montantComplementaires = 0;

			foreach( $apresPourCalculMontant as $apre ) {
				$decision = Set::classicExtract( $apre, 'Aideapre66.decisionapre' );
				$montantaccorde = Set::classicExtract( $apre, 'Aideapre66.montantaccorde' );

				if( !empty( $decision ) ) {
					if( $decision == 'ACC' ) {
						$montantComplementaires += $montantaccorde;
					}
				}
			}

// debug($montantComplementaires );

			if( $montantComplementaires > Configure::read( "Apre.montantMaxComplementaires" ) ) {
				$alerteMontantAides = true;
			}
			$this->set( 'apres', $apres );
			$this->set( 'alerteMontantAides', $alerteMontantAides );
			$this->set( 'montantComplementaires', $montantComplementaires );
			$this->_setOptions();
			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'index66' );
		}

		/**
		 * Ajax pour les coordonnées de la structure référente liée
		 *
		 * @param integer $structurereferente_id
		 */
		public function ajaxstruct( $structurereferente_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
			$dataStructurereferente_id = Set::extract( $this->request->data, "{$this->modelClass}.structurereferente_id" );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );
			$qd_struct = array(
				'conditions' => array(
					'Structurereferente.id' => $structurereferente_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$struct = $this->{$this->modelClass}->Structurereferente->find( 'first', $qd_struct );

			$this->set( 'struct', $struct );
			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'ajaxstruct', 'ajax' );
		}

		/**
		 * Ajax pour les coordonnées du référent APRE
		 *
		 * @param integer $referent_id
		 */
		public function ajaxref( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->request->data, "{$this->modelClass}.referent_id" ) );
			}
			// INFO: éviter les requêtes erronées du style ... WHERE "Referent"."id" = ''
			$referent = array( );
			if( !empty( $referent_id ) ) {
				$qd_referent = array(
					'conditions' => array(
						'Referent.id' => $referent_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$referent = $this->{$this->modelClass}->Referent->find( 'first', $qd_referent );
			}
//             $referent = $this->{$this->modelClass}->Referent->findbyId( $referent_id, null, null, -1 );
			$this->set( 'referent', $referent );
			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'ajaxref', 'ajax' );
		}

		/**
		 * Ajax pour les coordonnées du référent APRE
		 *
		 * @param integer $apre_id
		 */
		public function ajaxpiece( $apre_id = null ) { // FIXME
// 			$typeaideapre66_id = Set::classicExtract( $this->request->params, 'named.typeaideapre66_id' );
// 			$pieceadmin = explode( ',', Set::classicExtract( $this->request->params, 'named.pieceadmin' ) );
// 			$piececomptable = explode( ',', Set::classicExtract( $this->request->params, 'named.piececomptable' ) );
			$typeaideapre66_id = Set::classicExtract( $this->request->data, 'Aideapre66.typeaideapre66_id' );
// 			$pieceadmin = Set::classicExtract( $this->request->data, 'named.pieceadmin' );
// 			$piececomptable = Set::classicExtract( $this->request->data, 'named.piececomptable' );
// 			$this->request->data['Pieceaide66']['Pieceaide66'] = $pieceadmin;
// 			$this->request->data['Piececomptable66']['Piececomptable66'] = $piececomptable;

			if( !empty( $typeaideapre66_id ) ) {
				$typeaideapre66_id = suffix( $typeaideapre66_id );
			}
			else {
				$typeaideapre66_id = suffix( Set::extract( $this->request->data, 'Aideapre66.typeaideapre66_id' ) );
			}

			if( !empty( $typeaideapre66_id ) ) {
				$piecesadmin = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->Pieceaide66->find(
						'list', array(
					'fields' => array( 'Pieceaide66.id', 'Pieceaide66.name' ),
					'joins' => array(
						array(
							'table' => 'piecesaides66_typesaidesapres66',
							'alias' => 'Pieceaide66Typeaideapre66',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Pieceaide66Typeaideapre66.pieceaide66_id = Pieceaide66.id',
								'Pieceaide66Typeaideapre66.typeaideapre66_id' => $typeaideapre66_id,
							)
						)
					),
					'order' => array( 'Pieceaide66.name' ),
					'recursive' => -1
						)
				);

				$piecescomptable = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->Piececomptable66->find(
						'list', array(
					'fields' => array( 'Piececomptable66.id', 'Piececomptable66.name' ),
					'joins' => array(
						array(
							'table' => 'piecescomptables66_typesaidesapres66',
							'alias' => 'Piececomptable66Typeaideapre66',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Piececomptable66Typeaideapre66.piececomptable66_id = Piececomptable66.id',
								'Piececomptable66Typeaideapre66.typeaideapre66_id' => $typeaideapre66_id,
							)
						)
					),
					'order' => array( 'Piececomptable66.name' ),
					'recursive' => -1
						)
				);
				$typeaideapre = $this->request->data = $this->{$this->modelClass}->Aideapre66->find(
						'first', array(
					'conditions' => array(
						'Aideapre66.typeaideapre66_id' => $typeaideapre66_id
					),
					'contain' => array( 'Typeaideapre66' )
						)
				);
			}

// 			$typeaideapre = array();

			$this->request->data = array( );

			if( !empty( $apre_id ) ) {
				$aideapre66_existante = $this->{$this->modelClass}->Aideapre66->find(
						'first', array(
					'conditions' => array(
						'Aideapre66.apre_id' => $apre_id
					),
					'contain' => array(
						'Pieceaide66',
						'Piececomptable66'
					)
						)
				);

				if( !empty( $typeaideapre66_id ) ) {
					$typeaideapre = $this->request->data = $this->{$this->modelClass}->Aideapre66->find(
							'first', array(
						'conditions' => array(
							'Aideapre66.typeaideapre66_id' => $typeaideapre66_id
						),
						'contain' => array( 'Typeaideapre66' )
							)
					);
				}

				if( !empty( $typeaideapre66_id ) && ( $aideapre66_existante['Aideapre66']['typeaideapre66_id'] == $typeaideapre66_id ) ) {
					$this->request->data = array(
						'Pieceaide66' => array(
							'Pieceaide66' => Set::extract( '/Pieceaide66/id', $aideapre66_existante )
						),
						'Piececomptable66' => array(
							'Piececomptable66' => Set::extract( '/Piececomptable66/id', $aideapre66_existante )
						),
					);
				}
			}

			Configure::write( 'debug', 0 );
			$this->set( compact( 'piecesadmin', 'piecescomptable', 'typeaideapre' ) );

			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'ajaxpiece', 'ajax' );
		}

		/**
		 * Visualisation de l'APRE
		 *
		 * @param integer $apre_id
		 */
		public function view66( $apre_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->{$this->modelClass}->personneId( $apre_id ) ) ) );

			$this->Apre66->forceVirtualFields = true;

			$apre = $this->Apre66->find(
					'first', array(
				'conditions' => array(
					'Apre66.id' => $apre_id
				),
				'contain' => array(
					'Personne',
					'Referent',
					'Structurereferente',
					'Aideapre66'
				)
					)
			);

			$this->assert( !empty( $apre ), 'invalidParameter' );
			$this->Apre66->forceVirtualFields = false;

			$this->set( 'apre', $apre );
// 			debug( $apre );
			$this->set( 'personne_id', $apre['Apre66']['personne_id'] );
			$this->_setOptions();
			$this->set( 'urlmenu', '/apres66/index/'.$apre['Personne']['id'] );
			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'view66' );
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
				$personne_id = $id;
				$dossier_id = $this->Personne->dossierId( $personne_id );

				$qd_foyer = array(
					'conditions' => array(
						'Foyer.dossier_id' => $dossier_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$foyer = $this->Foyer->find( 'first', $qd_foyer );
				$foyer_id = Set::classicExtract( $foyer, 'Foyer.id' );
			}
			else if( $this->action == 'edit' ) {
				$apre_id = $id;

				$apre = $this->{$this->modelClass}->find(
					'first',
					array(
						'conditions' => array(
							'Apre66.id' => $apre_id
						),
						'contain' => array(
							'Personne',
							'Referent',
							'Structurereferente',
							'Aideapre66' => array(
								'Themeapre66',
								'Typeaideapre66',
								'Fraisdeplacement66',
								'Pieceaide66',
								'Piececomptable66'
							)
						)
					)
				);

				$this->assert( !empty( $apre ), 'invalidParameter' );

				$personne_id = $apre[$this->modelClass]['personne_id'];
				$dossier_id = $this->{$this->modelClass}->dossierId( $apre_id );

				$foyer_id = Set::classicExtract( $apre, 'Personne.foyer_id' );
			}
			$this->set( 'foyer_id', $foyer_id );
			$this->set( 'dossier_id', $dossier_id );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

            $this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}
			/**
			 *   Liste des APREs de la personne pour l'affichage de l'historique
			 *   lors de l'add/edit
			 * */
			$conditionsListeApres = array( "{$this->modelClass}.personne_id" => $personne_id );
			if( $this->action == 'edit' ) {
				$conditionsListeApres["{$this->modelClass}.id <>"] = $apre_id;
			}

			$listApres = $this->{$this->modelClass}->find(
				'all',
				array(
					'conditions' => $conditionsListeApres,
					'recursive' => -1
				)
			);
			$this->set( compact( 'listApres' ) );
			if( !empty( $listApres ) ) {
				$listesAidesSelonApre = $this->{$this->modelClass}->Aideapre66->find(
						'all', array(
					'conditions' => array(
						'Aideapre66.apre_id' => Set::extract( $listApres, "/{$this->modelClass}/id" ),
						'Aideapre66.decisionapre' => 'ACC'
					),
					'recursive' => -1
						)
				);
				$this->set( compact( 'listesAidesSelonApre' ) );
			}


			///Récupération de la liste des structures référentes liés uniquement à l'APRE
			$structs = $this->Structurereferente->listeParType( array( 'apre' => true ) );
			$this->set( 'structs', $structs );
			///Récupération de la liste des référents liés à l'APRE
			$referents = $this->Referent->listOptions();
			$this->set( 'referents', $referents );
			///Récupération de la liste des référents liés à l'APRE
			$typesaides = $this->Typeaideapre66->listOptions();
			$this->set( 'typesaides', $typesaides );


			///Personne liée au parcours
			$personne_referent = $this->Personne->PersonneReferent->find(
					'first', array(
				'conditions' => array(
					'PersonneReferent.personne_id' => $personne_id,
					'PersonneReferent.dfdesignation IS NULL'
				),
				'recursive' => -1
					)
			);


			///On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
			$personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id, $this->Session->read( 'Auth.User.id' ) );
			$this->set( 'personne', $personne );

			///Nombre d'enfants par foyer
			$nbEnfants = $this->Foyer->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
			$this->set( 'nbEnfants', $nbEnfants );

			if( !empty( $this->request->data ) ) {
                $this->Apre66->begin();
				/// Pour le nombre de pièces afin de savoir si le dossier est complet ou non
				$valide = false;
				$nbNormalPieces = array( );

				$typeaideapre66_id = suffix( Set::classicExtract( $this->request->data, 'Aideapre66.typeaideapre66_id' ) );
				$typeaide = array( );
				if( !empty( $typeaideapre66_id ) ) {

					$qd_typeaide = array(
						'conditions' => array(
							'Typeaideapre66.id' => $typeaideapre66_id
						),
						'fields' => null,
						'order' => null,
						'contain' => array(
							'Pieceaide66'
						)
					);
					$typeaide = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->find( 'first', $qd_typeaide );
// debug($typeaide);
// die();

					$nbNormalPieces['Typeaideapre66'] = count( Set::extract( $typeaide, '/Pieceaide66/id' ) );

					$key = 'Pieceaide66';
					if( isset( $this->request->data['Aideapre66'] ) && isset( $this->request->data[$key] ) && isset( $this->request->data[$key][$key] ) ) {
						$nbpieces = 0;
						if( !empty( $this->request->data[$key][$key] ) ) {
							foreach( $this->request->data[$key][$key] as $piece_key ) {
								if( !empty( $piece_key ) )
									$nbpieces++;
							}
						}
						$valide = ( $nbpieces == $nbNormalPieces['Typeaideapre66'] );
					}
				}
				$fields = array( 'isbeneficiaire', 'hascer', 'respectdelais' );
				foreach( $fields as $field ) {
					$valide = $this->request->data['Apre66'][$field] && $valide;
				}

				$this->request->data['Apre66']['etatdossierapre'] = ( $valide ? 'COM' : 'INC' );


				// Tentative d'enregistrement de l'APRE complémentaire
				$this->{$this->modelClass}->create( $this->request->data );
				$this->{$this->modelClass}->set( 'statutapre', 'C' );
				$success = $this->{$this->modelClass}->save();

				// Tentative d'enregistrement de l'aide liée à l'APRE complémentaire
				$this->{$this->modelClass}->Aideapre66->create( $this->request->data );

				if( !empty( $this->request->data['Fraisdeplacement66'] ) ) {

					$Fraisdeplacement66 = Hash::filter( (array)$this->request->data['Fraisdeplacement66'] );
					if( !empty( $Fraisdeplacement66 ) ) {
						$this->{$this->modelClass}->Aideapre66->Fraisdeplacement66->create( $this->request->data );
					}
				}

				if( $this->action == 'add' ) {
					$this->{$this->modelClass}->Aideapre66->set( 'apre_id', $this->{$this->modelClass}->getLastInsertID() );
				}
				$success = $this->{$this->modelClass}->Aideapre66->save() && $success;


				if( $this->action == 'add' ) {
					if( !empty( $Fraisdeplacement66 ) ) {
						$this->{$this->modelClass}->Aideapre66->Fraisdeplacement66->set( 'aideapre66_id', $this->{$this->modelClass}->Aideapre66->getLastInsertID() );
					}
				}
				if( !empty( $Fraisdeplacement66 ) ) {
					$success = $this->{$this->modelClass}->Aideapre66->Fraisdeplacement66->save() && $success;
				}


				/*
				  $Modecontact = Hash::expand( Hash::filter( (array)Hash::flatten( $this->request->data['Modecontact'] ) ) );
				  debug($Modecontact);
				  die();
				  if( !empty( $Modecontact ) ){
				  $success = $this->{$this->modelClass}->Personne->Foyer->Modecontact->saveAll( $Modecontact, array( 'validate' => 'first', 'atomic' => false ) ) && $success;
				  } */

				// Tentative d'enregistrement des pièces liées à une APRE selon ne aide donnée
				if( !empty( $this->request->data['Pieceaide66'] ) ) {
					$linkedData = array(
						'Aideapre66' => array(
							'id' => $this->{$this->modelClass}->Aideapre66->id
						),
						'Pieceaide66' => $this->request->data['Pieceaide66']
					);
					$success = $this->{$this->modelClass}->Aideapre66->save( $linkedData ) && $success;
				}


				// SAuvegarde des numéros ed téléphone si ceux-ci ne sont pas présents en amont
				$isDataPersonne = Hash::filter( (array)$this->request->data['Personne'] );
				if( !empty( $isDataPersonne ) ) {
					$success = $this->{$this->modelClass}->Personne->save( array( 'Personne' => $this->request->data['Personne'] ) ) && $success;
				}


				if( $success ) {
                    $this->{$this->modelClass}->commit();
                    $this->Jetons2->release( $dossier_id );
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $personne_id ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $apre;
				$this->request->data = Hash::insert(
								$this->request->data, "{$this->modelClass}.referent_id", Set::extract( $this->request->data, "{$this->modelClass}.structurereferente_id" ).'_'.Set::extract( $this->request->data, "{$this->modelClass}.referent_id" )
				);

				$typeaideapre66_id = Set::classicExtract( $this->request->data, 'Aideapre66.typeaideapre66_id' );
				$themeapre66_id = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->field( 'themeapre66_id', array( 'id' => $typeaideapre66_id ) );

				$this->request->data = Hash::insert( $this->request->data, 'Aideapre66.themeapre66_id', $themeapre66_id );
				$this->request->data = Hash::insert( $this->request->data, 'Aideapre66.typeaideapre66_id', "{$themeapre66_id}_{$typeaideapre66_id}" );

				///FIXME: doit faire autrement
				if( !empty( $this->request->data['Aideapre66']['Fraisdeplacement66'] ) ) {
					$this->request->data['Fraisdeplacement66'] = $this->request->data['Aideapre66']['Fraisdeplacement66'];
				}
				if( !empty( $this->request->data['Modecontact'] ) ) {
					$this->request->data['Modecontact'] = $personne['Foyer']['Modecontact'];
				}

				$this->request->data['Pieceaide66']['Pieceaide66'] = Set::extract( $apre, '/Aideapre66/Pieceaide66/id' );
				$this->request->data['Piececomptable66']['Piececomptable66'] = Set::extract( $apre, '/Aideapre66/Piececomptable66/id' );
			}

			// Doit-on setter les valeurs par défault ?
			$dataStructurereferente_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.structurereferente_id" );
			$dataReferent_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.referent_id" );

			// Si le formulaire ne possède pas de valeur pour ces champs, on met celles par défaut
			if( empty( $dataStructurereferente_id ) && empty( $dataReferent_id ) ) {
				$structurereferente_id = $referent_id = null;


				$structPersRef = Set::classicExtract( $personne_referent, 'PersonneReferent.structurereferente_id' );
				// Valeur par défaut préférée: à partir de personnes_referents
				if( !empty( $personne_referent ) && array_key_exists( $structPersRef, $structs ) ) {
					$structurereferente_id = Set::classicExtract( $personne_referent, 'PersonneReferent.structurereferente_id' );
					$referent_id = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
				}

				if( !empty( $structurereferente_id ) ) {
					$this->request->data = Hash::insert( $this->request->data, "{$this->modelClass}.structurereferente_id", $structurereferente_id );
				}
				if( !empty( $structurereferente_id ) && !empty( $referent_id ) ) {
					$this->request->data = Hash::insert( $this->request->data, "{$this->modelClass}.referent_id", preg_replace( '/^_$/', '', "{$structurereferente_id}_{$referent_id}" ) );
				}
			}


			$struct_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.structurereferente_id" );
			$this->set( 'struct_id', $struct_id );

			$referent_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.referent_id" );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );

			$this->set( 'personne_id', $personne_id );
			$this->_setOptions();
			$this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'add_edit_'.Configure::read( 'nom_form_apre_cg' ) );
		}

		/**
		 * Génère l'impression d'une APRE pour le CG 66.
		 * On prend la décision de ne pas le stocker.
		 *
		 * @param integer $id L'id de l'APRE que l'on veut imprimer.
		 * @return void
		 */
		public function impression( $id = null ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->{$this->modelClass}->personneId( $id ) ) );

			$pdf = $this->Apre66->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'apre_%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression de l\'APRE.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Imprime une notification d'APRE.
		 *
		 * @param integer $id L'id de l'APRE pour laquelle imprimer la notification.
		 * @return void
		 */
		public function notifications( $id = null ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->{$this->modelClass}->personneId( $id ) ) );

			$pdf = $this->Apre66->getNotificationAprePdf( $id );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'Notification_APRE_%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la notification d\'APRE.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Permet d'envoyer un mail au référent de la demande d'APRE pour lui indiquer
		 * qu'il manque des pièces à cette demande.
		 *
		 * @param integer $id
		 */
		public function maillink( $id = null ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->{$this->modelClass}->personneId( $id ) ) );

			$apre = $this->Apre66->find(
					'first', array(
				'conditions' => array(
					"Apre66.id" => $id
				),
				'contain' => array(
					'Personne',
					'Referent'
				)
					)
			);

			$this->assert( !empty( $apre ), 'error404' );

			if( !isset( $apre['Referent']['email'] ) || empty( $apre['Referent']['email'] ) ) {
				$this->Session->setFlash( "Mail non envoyé: adresse mail du référent ({$apre['Referent']['nom']} {$apre['Referent']['prenom']}) non renseignée.", 'flash/error' );
				$this->redirect( $this->referer() );
			}

			$success = true;
			try {
				$Email = new CakeEmail( 'apre66_piecesmanquantes' );
				if( Configure::read( 'debug' ) == 0 ) {
					$Email->to( $apre['Referent']['email'] );
				}
				else {
					$Email->to( $Email->from() );
				}
				$Email->subject( 'Demande d\'Apre' );

				$mailBody = "Bonjour,\n\nle dossier de demande APRE de {$apre['Personne']['qual']} {$apre['Personne']['nom']} {$apre['Personne']['prenom']} ne peut être validé car les fichiers ne sont pas joints dans WEBRSA.\n\nLaurence COSTE.";
				$result = $Email->send( $mailBody );

				$success = !empty( $result ) && $success;
			} catch( Exception $e ) {
				$this->log( $e->getMessage(), LOG_ERROR );
				$success = false;
			}

			if( $success ) {
				$this->Session->setFlash( 'Mail envoyé', 'flash/success' );
			}
			else {
				$this->Session->setFlash( 'Mail non envoyé', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}


		/**
		 * Fonction pour annuler une APRE pour le CG66
		 *
		 * @param integer $id
		 */
		public function cancel( $id ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->{$this->modelClass}->personneId( $id ) ) ) );

			$qd_apre = array(
				'conditions' => array(
					$this->modelClass.'.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$apre = $this->{$this->modelClass}->find( 'first', $qd_apre );

			$personne_id = Set::classicExtract( $apre, 'Apre66.personne_id' );
			$this->set( 'personne_id', $personne_id );

			$dossier_id = $this->{$this->modelClass}->dossierId( $id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->{$this->modelClass}->begin();

				$saved = $this->{$this->modelClass}->save( $this->request->data );
				$saved = $this->{$this->modelClass}->updateAllUnBound(
					array( 'Apre66.etatdossierapre' => '\'ANN\'' ),
					array(
						'"Apre66"."personne_id"' => $apre['Apre66']['personne_id'],
						'"Apre66"."id"' => $apre['Apre66']['id']
					)
				) && $saved;

				if( $saved ) {
					$this->{$this->modelClass}->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/erreur' );
				}
			}
			else {
				$this->request->data = $apre;
			}
			$this->set( 'urlmenu', '/apres66/index/'.$personne_id );

            $this->render( (CAKE_BRANCH == '1.2' ? '/apres/' : '/Apres/') .'cancel' );
		}

	}
?>
