<?php
	/**
	 * Code source de la classe OffresinsertionController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe OffresinsertionController ...
	 *
	 * @package app.Controller
	 */
	class OffresinsertionController extends AppController
	{

		public $name = 'Offresinsertion';
		public $uses = array( 'Offreinsertion', 'Actioncandidat', 'Contactpartenaire', 'Partenaire', 'Option' );

		public $helpers = array( 'Default2', 'Fileuploader', 'Csv' );

		public $commeDroit = array(
			'view' => 'Offresinsertion:index'
		);

        public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		public $components = array(
			'Search.Prg' => array(
				'actions' => array(
					'index' => array( 'filter' => 'Search' )
				)
			),
            'Fileuploader' => array(
                'colonneModele' => 'Actioncandidat'
            )
		);

		public function _setOptions() {
			$options = array();
			$options = $this->Actioncandidat->enums();
			$listeActions = $this->Actioncandidat->find( 'list', array( 'order' => 'Actioncandidat.name ASC' ) );
			$listePartenaires = $this->Actioncandidat->Partenaire->find( 'list', array( 'order' => 'Partenaire.libstruc ASC' ) );
			$listeContacts = $this->Actioncandidat->Contactpartenaire->find( 'list', array( 'order' => 'Contactpartenaire.nom ASC' ) );
			$options['Partenaire']['typevoie'] = $this->Option->typevoie();
			$correspondants = $this->Actioncandidat->ActioncandidatPersonne->Referent->find('list', array( 'order' => 'Referent.nom ASC' ) );

// 			$options = Set::merge( $options, $typevoie );
			$this->set( compact( 'options', 'listeActions', 'listePartenaires', 'listeContacts', 'typevoie', 'correspondants' ) );
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
		*   Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
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


		public function index() {
			if( !empty( $this->request->data ) ) {
                $querydatas = array(
                    'global' => $this->Offreinsertion->searchGlobal( $this->request->data ),
                    'actions' => $this->Offreinsertion->searchActions( $this->request->data ),
                    'contactpartenaires' => $this->Offreinsertion->searchContactpartenaires( $this->request->data ),
                    'partenaires' => $this->Offreinsertion->searchPartenaires( $this->request->data )
                );

                $results = array();
                foreach( $querydatas as $key => $querydata ) {
//                    $this->paginate = $querydata;
//                    $results[$key] = $this->paginate( $this->Actioncandidat );
                    $results[$key] = $this->Actioncandidat->find( 'all', $querydata );

                    // INFO: parce qu'on pagine dans une boucle sur le même modèle
//					$this->Components->unload( 'Paginator' );
//					$this->Components->unload( 'Search.ProgressivePaginator' );
                }
                $results['actions_par_partenaires'] = $results['partenaires'];
                
                
                // FIXME: liste des actions par partenaires
                foreach( $results['actions_par_partenaires'] as $i => $result ) {
                    $partenaire_id = Set::classicExtract( $result, 'Partenaire.id' );

                    $partenairesParContacts = $this->Contactpartenaire->find(
                        'all',
                        array(
                            'conditions' => array(
                                'Contactpartenaire.partenaire_id' => $partenaire_id
                            ),
                            'contain' => array(
                                'Actioncandidat' => array(
                                    'order' => array( 'Actioncandidat.name ASC', 'Actioncandidat.id ASC'  )
                                )
                            )
                        )
                    );
                   
                    $listeActions = Hash::extract( $partenairesParContacts, '{n}.Actioncandidat.{n}.name' );

                    sort($listeActions, SORT_REGULAR);

                    $partenairesParContacts = $listeActions;
                    $results['actions_par_partenaires'][$i]['Partenaire']['listeactions'] = $partenairesParContacts;
                }

				$this->set( compact( 'results' ) );
			}
			$this->_setOptions();
		}

		public function view( $actioncandidat_id = null ) {
			$this->assert( is_numeric( $actioncandidat_id ), 'error404' );

            $fichiers = array();
			$actioncandidat = $this->Actioncandidat->find(
				'first',
				array(
					'conditions' => array(
						'Actioncandidat.id' => $actioncandidat_id
					),
					'contain' => array(
						'Fichiermodule'
					)
				)
			);
			$this->assert( !empty( $actioncandidat ), 'invalidParameter' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'offresinsertion', 'action' => 'index' ) );
			}

            if( !empty( $this->request->data ) ) {
				$this->Actioncandidat->begin();

                $saved = $this->Actioncandidat->updateAllUnBound(
					array( 'Actioncandidat.haspiecejointe' => '\''.$this->request->data['Actioncandidat']['haspiecejointe'].'\'' ),
					array(
						'"Actioncandidat"."id"' => $actioncandidat_id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une action
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Actioncandidat.haspiecejointe" ), $actioncandidat_id ) && $saved;
				}

				if( $saved ) {
					$this->Actioncandidat->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $actioncandidat_id );
					$this->Actioncandidat->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $actioncandidat;
				$fichiers = $this->Fileuploader->fichiers( $actioncandidat['Actioncandidat']['id'] );
			}
			$this->Actioncandidat->commit();


			$this->_setOptions();
			$this->set( compact( 'fichiers', 'actioncandidat' ) );
		}

        /**
         * Fonction permettant d'exporter le tableau de résultats au format CSV
         */
        public function exportcsv() {

            $queryData = $this->Offreinsertion->search( Hash::expand( $this->request->params['named'], '__' ) );
			unset( $queryData['limit'] );

			$actionscandidat = $this->Actioncandidat->find( 'all', $queryData );

//debug($actionscandidat);
//die();
			$this->layout = '';
			$this->set( compact( 'headers', 'actionscandidat' ) );
			$this->_setOptions();
		}
	}
?>