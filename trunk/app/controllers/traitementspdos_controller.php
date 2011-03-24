<?php
	class TraitementspdosController extends AppController
	{
		public $name = 'Traitementspdos';

		public $uses = array( 'Traitementpdo', 'Propopdo', 'Personne', 'Dossier', 'Descriptionpdo', 'Traitementtypepdo' );

		/**
		* @access public
		*/

		public $components = array( 'Default' );

		public $helpers = array( 'Default2', 'Ajax', 'Locale', 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Traitementspdos:index',
			'add' => 'Traitementspdos:edit'
		);

		public $aucunDroit = array( 'ajaxstatutpersonne' );

		/**
		*
		*/

		protected function _options() {
			$options = $this->{$this->modelClass}->enums();

			$options[$this->modelClass]['descriptionpdo_id'] = $this->Descriptionpdo->find( 'list' );
			$options[$this->modelClass]['traitementtypepdo_id'] = $this->Traitementtypepdo->find( 'list' );
			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);

			$options[$this->modelClass]['listeDescription'] = $this->Descriptionpdo->find( 'all', array( 'contain' => false ) );

			$this->set( 'cloture', array( 0 => 'Non', 1 => 'Oui' ) );

			return $options;
		}

		/**
		*
		*/

		public function index( $id = null ) {
			$traitementspdos = $this->{$this->modelClass}->find(
				'all',
				array(
					'conditions' => array(
						'propopdo_id' => $id
					),
					'contain' => false
				)
			);
			$this->set( compact( 'traitementspdos' ) );

			// Dossier
			$pdo = $this->{$this->modelClass}->Propopdo->findById( $id, null, null, -1 );
			$this->set( 'pdo', $pdo );

			$personne_id = Set::classicExtract( $pdo, 'Propopdo.personne_id' );
			$pdo_id = Set::classicExtract( $pdo, 'Propopdo.id' );

			$this->set( 'personne_id', $personne_id );
			$this->set( 'pdo_id', $pdo_id );
			$this->set( 'options', $this->_options() );
		}

		/**
		*
		*/

		protected function _dirTraitementpdo( $action, $id, $type ) {
			return APP.'tmp'.DS.'files'.DS.session_id().DS.'Fichiertraitementpdo'.DS.$action.DS.$id.DS.$type;
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		*/

		public function ajaxfileupload() {
			$error = false;

			$dir = $this->_dirTraitementpdo( $this->params['url']['action'], $this->params['url']['primaryKey'], $this->params['url']['type'] );
			$path = $dir.DS.$this->params['url']['qqfile'];

			$old = umask(0);
			@mkdir( $dir, 0777, true );
			umask($old);

			$input = fopen( "php://input", "r" );
			$temp = tmpfile();
			$realSize = stream_copy_to_stream( $input, $temp );
			fclose( $input );

			if( $realSize != (int)$_SERVER["CONTENT_LENGTH"] ){
				$error = '$realSize != (int)$_SERVER["CONTENT_LENGTH"]';
			}

			$target = fopen( $path, "w" );
			fseek( $temp, 0, SEEK_SET );
			stream_copy_to_stream( $temp, $target );
			fclose( $target );

			Configure::write( 'debug', false );
			$this->layout = false;
			echo htmlspecialchars( json_encode( ( empty( $error ) ? array( 'success' => true ) : array( 'error' => $error ) ) ), ENT_NOQUOTES );
			die();
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		* FIXME: traiter les valeurs de retour
		*/

		public function ajaxfiledelete() {
			$dir = $this->_dirTraitementpdo( $this->params['pass'][0], $this->params['pass'][1], $this->params['pass'][2] );
			$path = $dir.DS.$this->params['pass'][3];
			$error = false;

			if( file_exists( $path ) ) {
				$error = !@unlink( $path );
			}

			if( $this->params['pass'][0] == 'edit' ) { // Suppression d'un document se trouvant déjà enregistré -> SSI c'est un edit
				$record = $this->Traitementpdo->Fichiertraitementpdo->find(
					'first',
					array(
						'fields' => array( 'id' ),
						'conditions' => array(
							'traitementpdo_id' => $this->params['pass'][1],
							'type' => $this->params['pass'][2],
							'name' => $this->params['pass'][3],
						)
					)
				);
				if( !empty( $record ) ) {
					$error = !$this->Traitementpdo->Fichiertraitementpdo->delete( $record['Fichiertraitementpdo']['id'] ) && $error;
				}
			}

			Configure::write( 'debug', false );
			$this->layout = false;
			echo htmlspecialchars( json_encode( ( empty( $error ) ? array( 'success' => true ) : array( 'error' => $error ) ) ), ENT_NOQUOTES );
			die();
		}

		/**
		*
		*/

		public function fileview() {
			$dir = $this->_dirTraitementpdo( $this->params['pass'][0], $this->params['pass'][1], $this->params['pass'][2] );
			$path = $dir.DS.$this->params['pass'][3];

			$file = array();

			if( file_exists( $path ) ) {
				$file = array(
					'name' => basename( $path ),
					'mime' => mime_content_type( $path ),
					'document' => file_get_contents( $path ),
					'length' => filesize( $path )
				);
			}
			else if( $this->params['pass'][0] == 'edit' ) {
				$record = $this->Traitementpdo->Fichiertraitementpdo->find(
					'first',
					array(
						'conditions' => array(
							'traitementpdo_id' => $this->params['pass'][1],
							'type' => $this->params['pass'][2],
							'name' => $this->params['pass'][3],
						),
						'recursive' => -1,
						'contain' => false
					)
				);
				if( !empty( $record ) ) {
					$file = $record['Fichiertraitementpdo'];
					if( empty( $file['document'] ) && !empty( $file['cmspath'] ) ) {
						$cmisFile = Cmis::read( $file['cmspath'], true );
						$file['document'] = $cmisFile['content'];
					}
					$file['length'] = strlen( $file['document'] );
				}
			}

			$this->assert( !empty( $file ), 'error404' );
			$this->assert( !empty( $file['document'] ), 'error404' );

			Configure::write( 'debug', false );
			$this->layout = false;

			header( "Content-type: {$file['mime']}" );
			header( "Content-Length: {$file['length']}" );
			header( "Content-Disposition: attachment; filename=\"{$file['name']}\"" );

			echo $file['document'];
			die();
		}

		/**
		* Récupération des fichiers en base
		*/

		protected function _fichiers( $traitementpdo_id ) {
			$fichiers = array();

			foreach( array( 'courrier', 'piecejointe' ) as $type ) {
				$tmpFiles = $this->Traitementpdo->Fichiertraitementpdo->find(
					'all',
					array(
						'fields' => array( 'name' ),
						'conditions' => array(
							'Fichiertraitementpdo.traitementpdo_id' => $traitementpdo_id,
							'Fichiertraitementpdo.type' => $type
						),
						'contain' => false
					)
				);

				$fichiers[$type] = Set::extract( $tmpFiles, '/Fichiertraitementpdo/name' );
			}

			return $fichiers;
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );


			$traitementpdo = $this->Traitementpdo->find(
				'first',
				array(
					'conditions' => array(
						'Traitementpdo.id' => $id,
					),
					'contain' => array(
						'Descriptionpdo',
						'Traitementtypepdo',
						'Personne' => array(
							'fields' => array(
								'Personne.nom',
								'Personne.prenom',
							)
						),
						'Fichiertraitementpdo' => array(
							'fields' => array(
								'Fichiertraitementpdo.name',
								'Fichiertraitementpdo.type',
								'Fichiertraitementpdo.created',
								'Fichiertraitementpdo.traitementpdo_id',
							)
						)
					)
				)
			);

			$this->assert( !empty( $traitementpdo ), 'invalidParameter' );

			$this->set( 'dossier_id', $this->Traitementpdo->dossierId( $id ) );

            // Retour à la page d'édition de la PDO
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'propospdos', 'action' => 'edit', Set::classicExtract( $traitementpdo, 'Traitementpdo.propopdo_id' ) ) );
            }

			$options = $this->Traitementpdo->enums();
			$this->set( compact( 'traitementpdo', 'options' ) );
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
		* FIXME: traiter le bouton "Retour"
		*/

		function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->Traitementpdo->begin();
			$fichiers = array( 'courrier' => array(), 'piecejointe' => array() );
			$this->set( 'options', $this->_options() );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$propopdo_id = $id;

				$propopdo = $this->Propopdo->findById( $id, null, null, -1 );
				$this->set( 'propopdo', $propopdo );
				$personne_id = Set::classicExtract( $propopdo, 'Propopdo.personne_id' );
				$dossier_id = $this->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$traitement_id = $id;
				$traitement = $this->Traitementpdo->find(
					'first',
					array(
						'conditions' => array(
							'Traitementpdo.id' => $traitement_id
						),
						'contain' => array(
							'Propopdo',
							'Descriptionpdo',
							'Traitementtypepdo',
							'Saisineepdpdo66',
						)
					)
				);

				$this->assert( !empty( $traitement ), 'invalidParameter' );

				$propopdo_id = Set::classicExtract( $traitement, 'Traitementpdo.propopdo_id' );
				$personne_id = Set::classicExtract( $traitement, 'Propopdo.personne_id' );
				$dossier_id = $this->Personne->dossierId( $personne_id );
			}

			$personnes = $this->Personne->Foyer->Dossier->find(
				'all',
				array(
					'fields'=>array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom'
					),
					'conditions'=>array(
						'Dossier.id'=>$dossier_id
					),
					'recursive' => -1,
					'joins' => array(
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
						),
						array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
						)
					)
				)
			);
			$listepersonnes = array();
			foreach($personnes as $personne) {
				$listepersonnes[$personne['Personne']['id']] = implode(
					' ',
					array(
						$personne['Personne']['qual'],
						$personne['Personne']['nom'],
						$personne['Personne']['prenom']
					)
				);
			}
			$this->set(compact('listepersonnes'));

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'propopdo_id', $propopdo_id );


			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Traitementpdo->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );


			if( !empty( $this->data ) ){
				if( $this->Traitementpdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = true;

					$saved = $this->Traitementpdo->sauvegardeTraitement( $this->data );

					if( $saved ) {
						// Début sauvegarde des fichiers attachés
						App::import ('Core', 'File' );
						foreach( array( 'courrier', 'piecejointe' ) as $type ) {
							$dir = $this->_dirTraitementpdo( $this->action, $this->params['pass'][0], $type );
							$oFolder = new Folder( $dir, true, 0777 );

							// Suppression des fichiers si besoin
							if( !Set::classicExtract( $this->data, "Traitementpdo.has{$type}" ) ) {
								$saved = $this->Traitementpdo->Fichiertraitementpdo->deleteAll(
									array(
										'Fichiertraitementpdo.traitementpdo_id' => $this->Traitementpdo->id,
										'Fichiertraitementpdo.type' => $type
									)
								) && $saved;
							}
							// Enregistrement des fichiers si besoin
							else {
								$files = $oFolder->find();
								if( !empty( $files ) ) {
									foreach( $files as $file ) {
										// Recherche de l'ancien enregistrement s'il existe
										$oldrecord = array(
											'Fichiertraitementpdo' => array(
												'name' => $file,
												'traitementpdo_id' => $this->Traitementpdo->id,
												'type' => $type,
												'mime' => mime_content_type( $dir.DS.$file  )
											)
										);
										$oldrecord = $this->Traitementpdo->Fichiertraitementpdo->find( 'first', array( 'conditions' => Set::flatten( $oldrecord ) ) );

										$record = array(
											'Fichiertraitementpdo' => array(
												'name' => $file,
												'traitementpdo_id' => $this->Traitementpdo->id,
												'type' => $type,
												'mime' => mime_content_type( $dir.DS.$file  ),
												'document' => file_get_contents( $dir.DS.$file ),
											)
										);
										$record = Set::merge( $oldrecord, $record );

										$this->Traitementpdo->Fichiertraitementpdo->create( $record );

										if( $tmpSaved = $this->Traitementpdo->Fichiertraitementpdo->save() ) {
											$oFile = new File( $dir.DS.$file, true );
											$tmpSaved = $oFile->delete() && $tmpSaved;
										}

										$saved = $tmpSaved && $saved;
									}
								}
							}

							// Suppression des fichiers temporaires
							if( $saved ) {
								$saved = $oFolder->delete( $dir ) && $saved;
							}
						}
						// Fin sauvegarde des fichiers attachés
					}

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Traitementpdo->commit(); // FIXME
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'propospdos', 'action' => 'edit', $propopdo_id ) );
					}
					else {
						$this->Traitementpdo->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$fichiers = $this->_fichiers( $id );

					// FIXME: Début ajout des fichiers stockés en attente
					foreach( array( 'courrier', 'piecejointe' ) as $type ) {
						$dir = $this->_dirTraitementpdo( $this->action, $this->params['pass'][0], $type );
						$oFolder = new Folder( $dir, true, 0777 );
						$files = $oFolder->find();
						if( !empty( $files ) ) {
							foreach( $files as $file ) {
								$found = false;
								if( !empty( $fichiers ) ) {
									foreach( $fichiers[$type] as $i => $oldfile ) {
										if( $oldfile == $file ) {
											$found = $i;
										}
									}
								}

								if( $found !== false ) {
									$fichiers[$type][$found] = $file;
								}
								else {
									$fichiers[$type][] = $file;
								}
							}
						}
					}
					// Fin ajout des fichiers stockés en attente

					$this->Traitementpdo->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $traitement;
				$fichiers = $this->_fichiers( $id );
			}

			$this->Traitementpdo->commit();

			$traitementspdosouverts = $this->{$this->modelClass}->find(
				'all',
				array(
					'conditions' => array(
						'Traitementpdo.propopdo_id' => $id,
						'Traitementpdo.clos' => 0
					)
				)
			);

			$this->set( compact( 'traitementspdosouverts', 'fichiers' ) );

			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		function ajaxstatutpersonne( $personne_id = null ) {
			$dataTraitementpdo_id = Set::extract( $this->data, 'Traitementpdo.personne_id' );
			$personne_id = ( empty( $personne_id ) && !empty( $dataTraitementpdo_id ) ? $dataTraitementpdo_id : $personne_id );
			$personne = $this->Traitementpdo->Propopdo->find(
				'first',
				array(
					'conditions'=>array(
						'Propopdo.personne_id' => $personne_id
					),
					'contain'=>array(
						'Statutpdo'
					),
					'order'=>array(
						'Propopdo.datereceptionpdo DESC'
					)
				)
			);
			$this->set( 'values', $personne );
			Configure::write( 'debug', 0 );
			$this->render( 'statutpersonne', 'ajax' );
		}

		/**
		*
		*/

		public function gedooo( $id = null ) {

		}

		/**
		*
		*/

		public function clore($id = null) {
			$traitementpdo = $this->Traitementpdo->find(
				'first',
				array(
					'conditions'=>array(
						'Traitementpdo.id'=>$id
					)
				)
			);
			$this->assert( !empty( $traitementpdo ), 'invalidParameter' );

			$this->Traitementpdo->id=$id;
			$this->Traitementpdo->saveField('clos', Configure::read( 'traitementClosId' ));
			$this->redirect(array( 'controller'=> 'propospdos', 'action'=>'edit', $traitementpdo['Traitementpdo']['propopdo_id']));
		}

		/**
		*
		*/

		public function delete($id = null) {
			$traitementpdo = $this->Traitementpdo->find(
				'first',
				array(
					'conditions'=>array(
						'Traitementpdo.id'=>$id
					)
				)
			);
			$this->assert( !empty( $traitementpdo ), 'invalidParameter' );

			$this->Traitementpdo->delete($id);
			$this->redirect(array( 'controller'=> 'propospdos', 'action'=>'edit', $traitementpdo['Traitementpdo']['propopdo_id']));
		}
	}
?>
