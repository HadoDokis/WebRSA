<?php
    /**
     * Code source de la classe Decisionscuis66Controller.
     *
     * PHP 5.3
     *
     * @package app.Controller
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
     * La classe Decisionscuis66Controller ...
     *
     * @package app.Controller
     */
    class Decisionscuis66Controller extends AppController
    {
        public $name = 'Decisionscuis66';

        public $uses = array( 'Decisioncui66', 'Option' );

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
			'decisioncui' => 'read',
			'delete' => 'delete',
			'edit' => 'update',
			'impression' => 'read',
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
			$options = $this->Decisioncui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Decisioncui66->Cui->enums(),
				$this->Decisioncui66->Cui->Propodecisioncui66->enums(),
				$options
			);
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

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Decisioncui66->personneId( $id ) ) ) );

			$fichiers = array( );
			$decisioncui66 = $this->Decisioncui66->find(
				'first',
				array(
					'conditions' => array(
						'Decisioncui66.id' => $id
					),
					'contain' => array(
						'Cui',
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $decisioncui66['Cui']['personne_id'];
			$cui_id = $decisioncui66['Cui']['id'];
			$dossier_id = $this->Decisioncui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'decisioncui', $cui_id ) );
			}

			if( !empty( $this->request->data ) ) {
                $this->Decisioncui66->begin();
				$saved = $this->Decisioncui66->updateAllUnBound(
					array( 'Decisioncui66.haspiecejointe' => '\''.$this->request->data['Decisioncui66']['haspiecejointe'].'\'' ),
					array(
						'"Decisioncui66"."cui_id"' => $cui_id,
						'"Decisioncui66"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Decisioncui66.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Decisioncui66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Decisioncui66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'decisioncui66' ) );
			$this->set( 'urlmenu', '/decisionscuis66/decisioncui/'.$cui_id );
		}
		
		
		
		/**
		 *
		 * @param integer $cui_id
		 */
		public function decisioncui( $cui_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Decisioncui66->Cui->personneId( $cui_id ) ) ) );

			$nbrCuis = $this->Decisioncui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Decisioncui66->Cui->find(
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

			$decisionscuis66 = $this->Decisioncui66->find(
				'all',
				array(
					'fields' => array_merge(
						$this->Decisioncui66->fields(),
						array(
							$this->Decisioncui66->Fichiermodule->sqNbFichiersLies( $this->Decisioncui66, 'nb_fichiers_lies' )
						)
					),
					'conditions' => array(
						'Decisioncui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'decisionscuis66', 'personne_id' ) );
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
				$decisioncui66_id = $id;
				$decisioncui66 = $this->Decisioncui66->find(
					'first',
					array(
						'conditions' => array(
							'Decisioncui66.id' => $decisioncui66_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);
				$this->set( 'decisioncui66', $decisioncui66 );

				$cui_id = Set::classicExtract( $decisioncui66, 'Decisioncui66.cui_id' );
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Decisioncui66->Cui->personneId( $cui_id ) ) ) );


			// CUI en lien avec la proposition
			$cui = $this->Decisioncui66->Cui->find(
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


			// Récupération des avis proposés sur le CUI
			$proposdecisionscuis66 = $this->Decisioncui66->Cui->Propodecisioncui66->find(
				'all',
				array(
					'conditions' => array(
						'Propodecisioncui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);
			$this->set( compact( 'proposdecisionscuis66' ) );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );


			$dossier_id = $this->Decisioncui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
                $this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'decisionscuis66', 'action' => 'decisioncui', $cui_id ) );
			}


			if ( !empty( $this->request->data ) ) {
                $this->Decisioncui66->begin();

                $saved = $this->Decisioncui66->save( $this->request->data );

                if( $saved ) {
                    $decisioncui66_id = $this->Decisioncui66->id;
                    $saved = $this->Decisioncui66->Cui->updatePositionFromDecisioncui66( $decisioncui66_id ) && $saved;
                }
                    
                if( $saved ) {
                    $this->Decisioncui66->commit();
                    $this->Jetons2->release( $dossier_id );
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'decisionscuis66', 'action' => 'decisioncui', $cui_id ) );
                }
                else {
                    $this->Decisioncui66->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
			}
			else{
				if( $this-> action == 'edit' ){
					$this->request->data = $decisioncui66;
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( 'add_edit' );
        }

		/**
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Decisioncui66->personneId( $id ) ) );

			$this->Default->delete( $id );
		}


		/**
		 * Imprime la notification au bénéficiaire pour le CUI.
		 *
		 * @param integer $id L'id de la décision sur le CUI que l'on veut imprimer
		 * @return void
		 */
		public function impression( $id, $destinataire ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Decisioncui66->personneId( $id ) ) );

			$pdf = $this->Decisioncui66->getDefaultPdf( $id, $destinataire, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'cui_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de décision', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

    }
?>
