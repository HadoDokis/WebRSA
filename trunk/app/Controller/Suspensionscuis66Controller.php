<?php
    /**
     * Code source de la classe Suspensionscuis66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe Suspensionscuis66Controller ...
     *
     * @package app.Controller
     */
    class Suspensionscuis66Controller extends AppController
    {
        public $name = 'Suspensionscuis66';

        public $uses = array( 'Suspensioncui66', 'Option' );

        public $helpers = array( 'Default2', 'Fileuploader', 'Default' );

        public $components = array( 'Fileuploader', 'Jetons2', 'Default', 'DossiersMenus' );


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
			'index' => 'read',
		);
        
        public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download', 'ajaxetatpdo' );

		/**
		 *
		 */
        protected function _setOptions() {
			$options = $this->Suspensioncui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Suspensioncui66->Cui->enums(),
				$options
			);
             $listeMotifssuspensioncuis66 = $this->Suspensioncui66->Motifsuspensioncui66->find(
                'list',
                array(
                    'order' => array( 'Motifsuspensioncui66.name ASC' )
                )
            );
             $this->set( compact( 'options', 'listeMotifssuspensioncuis66' ) );

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
		 * Fonction permettant d'accéder à la page pour lier les fichiers au CER
		 *
		 * @param type $id
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Suspensioncui66->Cui->dossierId( $id ) ) ) );

			$fichiers = array( );
			$suspensioncui66 = $this->Suspensioncui66->find(
				'first',
				array(
					'conditions' => array(
						'Suspensioncui66.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						),
						'Cui'
					)
				)
			);

			$cui_id = $suspensioncui66['Suspensioncui66']['cui_id'];
            
            $personne_id = $suspensioncui66['Cui']['personne_id'];
            
			$dossier_id = $this->Suspensioncui66->Cui->Personne->dossierId( $personne_id );

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'suspensionscuis66', 'action' => 'index', $cui_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Suspensioncui66->begin();

				$saved = $this->Suspensioncui66->updateAllUnBound(
					array( 'Suspensioncui66.haspiecejointe' => '\''.$this->request->data['Suspensioncui66']['haspiecejointe'].'\'' ),
					array(
						'"Suspensioncui66"."cui_id"' => $cui_id,
						'"Suspensioncui66"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Suspensioncui66.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Suspensioncui66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Suspensioncui66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			$this->_setOptions();
			$this->set( 'dossier_id', $dossier_id);
			$this->set( compact( 'personne_id', 'fichiers', 'suspensioncui66' ) );
			$this->set( 'urlmenu', '/suspensionscuis66/index/'.$cui_id );
		}

		/**
		 *
		 * @param integer $cui_id
		 */
		public function index( $cui_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Suspensioncui66->Cui->personneId( $cui_id ) ) ) );

			$nbrCuis = $this->Suspensioncui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Suspensioncui66->Cui->find(
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

			$suspensionscuis66 = $this->Suspensioncui66->find(
				'all',
				array(
                    'fields' => array_merge(
                        $this->Suspensioncui66->fields(),
                        array(
                            $this->Suspensioncui66->Fichiermodule->sqNbFichiersLies( $this->Suspensioncui66, 'nb_fichiers_lies' )
                        )
                    ),
					'conditions' => array(
						'Suspensioncui66.cui_id' => $cui_id
					),
					'contain' => array(
                        'Motifsuspensioncui66'
                    )
				)
			);

            foreach( $suspensionscuis66 as $i => $suspensioncui66 ) {
                $listMotifs = Hash::extract( $suspensioncui66, 'Motifsuspensioncui66.{n}.name' );
                $suspensionscuis66[$i]['Suspensioncui66']['listmotifs'] = $listMotifs;
            }

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'suspensionscuis66' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

			// Retour à la liste des CUI en cas de retour
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
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

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$suspensioncui66_id = $id;
				$suspensioncui66 = $this->Suspensioncui66->find(
					'first',
					array(
						'conditions' => array(
							'Suspensioncui66.id' => $suspensioncui66_id
						),
						'contain' => array(
                            'Motifsuspensioncui66'
                        )
					)
				);
				$this->set( 'decisioncui66', $suspensioncui66 );

				$cui_id = Set::classicExtract( $suspensioncui66, 'Suspensioncui66.cui_id' );
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Suspensioncui66->Cui->personneId( $cui_id ) ) ) );

			// CUI en lien avec la proposition
			$cui = $this->Suspensioncui66->Cui->find(
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

            $dossier_id = $this->Suspensioncui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
                $this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'suspensionscuis66', 'action' => 'index', $cui_id ) );
			}


			if ( !empty( $this->request->data ) ) {
                $this->Suspensioncui66->begin();
				if( $this->Suspensioncui66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Suspensioncui66->save( $this->request->data );

					if( $saved ) {
						$this->Suspensioncui66->commit();
                        $this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'suspensionscuis66', 'action' => 'index', $cui_id ) );
					}
					else {
						$this->Suspensioncui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->request->data = $suspensioncui66;
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( 'edit' );
        }

		/**
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Suspensioncui66->personneId( $id ) ) );

			$this->Default->delete( $id );
		}
    }
?>
