<?php
    /**
     * Code source de la classe Accompagnementscuis66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe Accompagnementscuis66Controller ...
     *
     * @package app.Controller
     */
    class Accompagnementscuis66Controller extends AppController
    {
        public $name = 'Accompagnementscuis66';

        public $uses = array( 'Accompagnementcui66', 'Option' );

        public $helpers = array( 'Default2', 'Default', 'Fileuploader', 'Cake1xLegacy.Ajax' );

        public $components = array( 'Jetons2', 'Default', 'Gedooo.Gedooo', 'DossiersMenus', 'Fileuploader' );
        
        public $aucunDroit = array( 'ajaxtaux','ajaxfileupload', 'fileview', 'download' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'delete' => 'delete',
			'edit' => 'update',
			'impression' => 'read',
			'index' => 'read',
			'view' => 'read',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'download' => 'read',
			'filelink' => 'read',
			'fileview' => 'read'
		);

		/**
		 *
		 */
        protected function _setOptions() {
			$options = $this->Accompagnementcui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Accompagnementcui66->Cui->enums(),
				$options
			);
			$typevoie = $this->Option->typevoie();
			$options = Hash::insert( $options, 'typevoie', $typevoie );

			$secteursactivites = $this->Accompagnementcui66->Cui->Personne->Dsp->Libsecactderact66Secteur->find(
					'list',
					array(
						'contain' => false,
						'order' => array( 'Libsecactderact66Secteur.code' )
					)
				);
			$this->set( 'secteursactivites', $secteursactivites );

			$codesromemetiersdsps66 = $this->Accompagnementcui66->Cui->Personne->	Dsp->Libderact66Metier->find(
					'all',
					array(
						'contain' => false,
						'order' => array( 'Libderact66Metier.code' )
					)
				);
			foreach( $codesromemetiersdsps66 as $coderomemetierdsp66 ) {
				$options['Coderomemetierdsp66'][$coderomemetierdsp66['Libderact66Metier']['coderomesecteurdsp66_id'].'_'.$coderomemetierdsp66['Libderact66Metier']['id']] = $coderomemetierdsp66['Libderact66Metier']['code'].'. '.$coderomemetierdsp66['Libderact66Metier']['name'];
			}

			$this->set( 'options', $options );
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
		 * Fonction permettant d'accéder à la page pour lier les fichiers auw avis techniques
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Accompagnementcui66->personneId( $id ) ) ) );

			$fichiers = array( );
			$accompagnementcui66 = $this->Accompagnementcui66->find(
				'first',
				array(
					'conditions' => array(
						'Accompagnementcui66.id' => $id
					),
					'contain' => array(
						'Cui',
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $accompagnementcui66['Cui']['personne_id'];
			$cui_id = $accompagnementcui66['Cui']['id'];
			$dossier_id = $this->Accompagnementcui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $cui_id ) );
			}

			if( !empty( $this->request->data ) ) {
                $this->Accompagnementcui66->begin();
				$saved = $this->Accompagnementcui66->updateAllUnBound(
					array( 'Accompagnementcui66.haspiecejointe' => '\''.$this->request->data['Accompagnementcui66']['haspiecejointe'].'\'' ),
					array(
						'"Accompagnementcui66"."cui_id"' => $cui_id,
						'"Accompagnementcui66"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Accompagnementcui66.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Accompagnementcui66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Accompagnementcui66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'accompagnementcui66' ) );
			$this->set( 'urlmenu', '/accompagnementscuis66/index/'.$cui_id );
		}
		
		
		/**
		 *
		 * @param integer $cui_id
		 */
		public function index( $cui_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Accompagnementcui66->Cui->personneId( $cui_id ) ) ) );

			$nbrCuis = $this->Accompagnementcui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Accompagnementcui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);
			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );

			$accompagnementscuis66 = $this->Accompagnementcui66->find(
				'all',
				array(
					'fields' => array_merge(
						$this->Accompagnementcui66->fields(),
						array(
							$this->Accompagnementcui66->Fichiermodule->sqNbFichiersLies( $this->Accompagnementcui66, 'nb_fichiers_lies' )
						)
					),
					'conditions' => array(
						'Accompagnementcui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'accompagnementscuis66' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

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

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$accompagnementcui66_id = $id;
				$accompagnementcui66 = $this->Accompagnementcui66->find(
					'first',
					array(
						'conditions' => array(
							'Accompagnementcui66.id' => $accompagnementcui66_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);
				$this->set( 'decisioncui66', $accompagnementcui66 );

				$cui_id = Set::classicExtract( $accompagnementcui66, 'Accompagnementcui66.cui_id' );
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Accompagnementcui66->Cui->personneId( $cui_id ) ) ) );

			// CUI en lien avec la proposition
			$cui = $this->Accompagnementcui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);


			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );

			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui', $cui );
			$this->set( 'cui_id', $cui_id );


			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			$dossier_id = $this->Accompagnementcui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

            $this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'accompagnementscuis66', 'action' => 'index', $cui_id ) );
			}

			if ( !empty( $this->request->data ) ) {
                $this->Accompagnementcui66->begin();

				if( $this->Accompagnementcui66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Accompagnementcui66->save( $this->request->data );

					if( $saved ) {
						$this->Accompagnementcui66->commit();
                        $this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'accompagnementscuis66', 'action' => 'index', $cui_id ) );
					}
					else {
						$this->Accompagnementcui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->request->data = $accompagnementcui66;
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( 'add_edit' );
        }


		/**
		 * Imprime un document pour les périodes d'immersion liées au CUI.
		 *
		 * @param integer $id L'id de la période d'immersion que l'on veut imprimer
		 * @return void
		 */
		public function impression( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Accompagnementcui66->personneId( $id ) ) );

			$pdf = $this->Accompagnementcui66->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'periodeimmersion_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la période d\'immersion.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Accompagnementcui66->personneId( $id ) ) );

			$this->Default->delete( $id );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function view( $id ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Accompagnementcui66->personneId( $id ) ) ) );

			$this->_setOptions();
			$this->Default->view( $id );
		}
    }
?>
