<?php

	class ActionscandidatsController extends AppController
	{
		public $name = 'Actionscandidats';
		public $uses = array( 'Actioncandidat', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Theme', 'Default2', 'Fileuploader' );
		public $components = array( 'Default', 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Actionscandidats:index',
			'add' => 'Actionscandidats:edit'
		);

		/**
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();
			$options = array();
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( compact( 'options', 'typevoie' ) );
			return $return;
		}

		protected function _setOptions() {
			$options = $this->Actioncandidat->enums();

			if( $this->action != 'index' ) {
				$options['Actioncandidat']['referent_id'] = $this->Actioncandidat->ActioncandidatPersonne->Referent->find('list');

				$options['Zonegeographique'] = $this->Actioncandidat->Zonegeographique->find( 'list' );
				$zonesselected = $this->Actioncandidat->Zonegeographique->find( 'list', array( 'fields' => array( 'id' ) ) );
				$this->set( compact( 'zonesselected' ) );
				$this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );

// 				if( Configure::read( 'Cg.departement' ) == 66 ) {
					$conditionsChargeinsertionSecretaire = Configure::read( 'Chargeinsertion.Secretaire.group_id' );
					if( $conditionsChargeinsertionSecretaire != NULL ) {
						$conditionsChargeinsertion = array(
							'Chargeinsertion.nom IS NOT NULL',
							"Chargeinsertion.group_id" => $conditionsChargeinsertionSecretaire
						);

						$conditionsSecretaire = array(
							'Secretaire.nom IS NOT NULL',
							"Secretaire.group_id" => $conditionsChargeinsertionSecretaire
						);
					}
					else {
						$conditionsChargeinsertion = $conditionsSecretaire = array();
					}
					$options['Actioncandidat']['chargeinsertion_id'] = $this->Actioncandidat->Chargeinsertion->find(
						'list',
						array(
							'fields' => array( 'id', 'nom_complet' ),
							'conditions' => $conditionsChargeinsertion,
							'order' => 'Chargeinsertion.nom ASC'
						)
					);
					$options['Actioncandidat']['secretaire_id'] = $this->Actioncandidat->Secretaire->find(
						'list',
						array(
							'fields' => array( 'id', 'nom_complet' ),
							'conditions' => $conditionsSecretaire,
							'order' => 'Secretaire.nom ASC'
						)
					);
// 				}
			}

			foreach( array( 'Contactpartenaire') as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}
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


		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->Actioncandidat->forceVirtualFields = true;
			$this->Actioncandidat->recursive = -1;

			$this->paginate = array(
                            'contain' => array(
				'Contactpartenaire' => array(
                                    'Partenaire'
				),
				'Chargeinsertion',
				'Secretaire'
                            ),
                            'fields' => array_merge(
				$this->Actioncandidat->fields(),
				array(
                                    $this->Actioncandidat->Fichiermodule->sqNbFichiersLies( $this->Actioncandidat, 'nb_fichiers_lies' )
                            	)
                            ),
                            'limit' => '1000'
			);

			$this->set(
				Inflector::tableize( $this->modelClass ),
				$this->paginate( $this->modelClass )
			);
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

		protected function _add_edit($id = null){

			$actioncandidat = $this->Actioncandidat->find(
				'first',
				array(
					'fields' => $this->Actioncandidat->fields(),
					'conditions' => array(
						'Actioncandidat.id' => $id
					),
					'contain' => array(
						'Fichiermodule',
						'Zonegeographique'
					)
				)
			);

			$fichiers = array();
			if( !empty( $this->data ) ) {
				$this->Actioncandidat->begin();
				$saved = $this->Actioncandidat->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers(
						$dir,
						!Set::classicExtract( $this->data, "Actioncandidat.haspiecejointe" ),
						( ( $this->action == 'add' ) ? $this->Actioncandidat->id : $id )
					) && $saved;
				}

				if( $saved ) {
					$this->Actioncandidat->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array(  'controller' => 'actionscandidats','action' => 'index' ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Actioncandidat->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $actioncandidat;
				$fichiers = $this->Fileuploader->fichiers( $actioncandidat['Actioncandidat']['id'] );
			}
			$this->Actioncandidat->commit();

			$this->set( compact( '"actioncandidat', 'fichiers' ) );
			$this->_setOptions();
			$this->render( $this->action, null, 'add_edit' );
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
		}
	}
?>