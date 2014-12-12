<?php
	/**
	 * Code source de la classe Piecesmailscuis66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

    App::import( 'Behaviors', 'Occurences' );
	/**
	 * La classe Piecesmailscuis66Controller ...
	 *
	 * @package app.Controller
	 */
	class Piecesmailscuis66Controller extends AppController
	{
		public $name = 'Piecesmailscuis66';
		public $uses = array( 'Piecemailcui66', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' , 'Fileuploader');
		public $components = array( 'Default', 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Piecesmailscuis66:index'
		);
        
        public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

        public $crudMap = array(
			'add' => 'create',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'fileview' => 'read',
			'index' => 'read',
			'view' => 'read',
		);

        
        
		protected function _setOptions() {
			$options = $this->Piecemailcui66->enums();
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
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
//			$this->Piecemailcui66->Behaviors->attach( 'Occurences' );
//  
//            $querydata = $this->Piecemailcui66->qdOccurencesExists(
//                array(
//                    'fields' => $this->Piecemailcui66->fields(),
//                    'order' => array( 'Piecemailcui66.name ASC' ),
//                )
//            );

            
            $querydata = array(
                'fields' => array_merge(
                    $this->Piecemailcui66->fields(),
                    array(
                        $this->Piecemailcui66->Fichiermodule->sqNbFichiersLies( $this->Piecemailcui66, 'nb_fichiers_lies' )
                    )
                ),
                'contain' => false,
                'recursive' => -1,
                'order' => array( 'Piecemailcui66.name ASC' ),
            );
            $this->paginate = $querydata;
            $piecesmailscuis66 = $this->paginate('Piecemailcui66');
            $this->set( compact('piecesmailscuis66'));
            $this->_setOptions();
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
		*/

		protected function _add_edit( $id = null){
            // Retour à la liste en cas d'annulation
            if( isset( $this->request->data['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'piecesmailscuis66', 'action' => 'index' ) );
            }

            $fichiers = array();
			if( !empty( $this->request->data ) ) {
                $this->Piecemailcui66->begin();
                
				$this->Piecemailcui66->create( $this->request->data );
				$success = $this->Piecemailcui66->save();
                
                if( $success ){
                // Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers(
						$dir,
						!Set::classicExtract( $this->request->data, "Piecemailcui66.haspiecejointe" ),
						( ( $this->action == 'add' ) ? $this->Piecemailcui66->id : $id )
					) && $saved;
                }
                
                if( $success ) {
					$this->Piecemailcui66->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Piecemailcui66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}

                
//				$this->_setFlashResult( 'Save', $success );
//				if( $success ) {
//					$this->redirect( array( 'action' => 'index' ) );
//				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Piecemailcui66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Piecemailcui66.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
                
                $fichiersEnBase = $this->Piecemailcui66->Fichiermodule->find(
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
							'Fichiermodule.modele' => 'Piecemailcui66',
							'Fichiermodule.fk_value' => $id,
						),
						'contain' => false
					)
				);
				$fichiersEnBase = Set::classicExtract( $fichiersEnBase, '{n}.Fichiermodule' );
				$this->set( 'fichiersEnBase', $fichiersEnBase );
			}

            $this->set( 'fichiers', $fichiers );
            $this->_setOptions();
			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->Default->view( $id );
            $this->_setOptions();
		}
	}
?>