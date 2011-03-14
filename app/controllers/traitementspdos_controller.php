<?php
    class TraitementspdosController extends AppController
    {
        public $name = 'Traitementspdos';

        public $uses = array( 'Traitementpdo', 'Propopdo', 'Personne', 'Dossier', 'Descriptionpdo', 'Traitementtypepdo' );
        /**
        * @access public
        */

        public $components = array( 'Default' );

        public $helpers = array( 'Default2', 'Ajax', 'Locale' );

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

        public function view( $id = null ) {
            $this->{$this->modelClass}->recursive = -1;
            $this->Default->view( $id );
        }

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		*/

		public function ajaxfileupload() {
			$error = false;

			$dir = APP.'tmp'.DS.'files'.DS.'fichierstraitementspdos'.DS.session_id().DS.$this->params['url']['action'].DS.$this->params['url']['primaryKey'];
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
		*/

		public function ajaxfiledelete() {
			$dir = APP.'tmp'.DS.'files'.DS.'fichierstraitementspdos'.DS.session_id().DS.$this->params['pass'][0].DS.$this->params['pass'][1];
			$path = $dir.DS.$this->params['pass'][2];
			$error = false;

			if( file_exists( $path ) ) {
				@unlink( $path );
			}

			Configure::write( 'debug', false );
			$this->layout = false;
			echo htmlspecialchars( json_encode( ( empty( $error ) ? array( 'success' => true ) : array( 'error' => $error ) ) ), ENT_NOQUOTES );
			die();
		}

       /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _add_edit( $id = null ) {
            $this->assert( valid_int( $id ), 'invalidParameter' );

            $this->Traitementpdo->begin();
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
                $traitement = $this->Traitementpdo->findById( $traitement_id, null, null, 1 );
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
						$dir = APP.'tmp'.DS.'files'.DS.'fichierstraitementspdos'.DS.session_id().DS.$this->action.DS.$this->params['pass'][0];
						$oFolder = new Folder( $dir, true );
						$files = $oFolder->find();
						if( !empty( $files ) ) {
							foreach( $files as $file ) {
								$record = array(
									'Fichiertraitementpdo' => array(
										'name' => $file,
										'traitementpdo_id' => $this->Traitementpdo->id,
										'type' => 'courrier',
										'mime' => mime_content_type( $dir.DS.$file  ),
										'document' => file_get_contents( $dir.DS.$file ),
									)
								);
								$this->Traitementpdo->Fichiertraitementpdo->create( $record );

								if( $tmpSaved = $this->Traitementpdo->Fichiertraitementpdo->save() ) {
									$oFile = new File( $dir.DS.$file, true );
									$tmpSaved = $oFile->delete() && $tmpSaved;
								}

								$saved = $tmpSaved && $saved;
							}
						}

						if( $saved ) {
							$saved = $oFolder->delete( $dir ) && $saved;
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
                    $this->Traitementpdo->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
            }
            elseif( $this->action == 'edit' )
                $this->data = $traitement;

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
            $this->set( compact( 'traitementspdosouverts' ) );

            $this->render( $this->action, null, 'add_edit' );
        }

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
