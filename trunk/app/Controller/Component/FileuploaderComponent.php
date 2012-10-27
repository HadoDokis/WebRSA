<?php
	/**
	 * Fichier source de la classe FileuploaderComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('Folder', 'Utility');
	App::uses('File', 'Utility');

	/**
	 * La classe FileuploaderComponent permet de gérer les fichiers liés pour
	 * différents modules (contrôleurs).
	 *
	 * @see http://valums.com/ajax-upload/
	 * @see http://doc.ubuntu-fr.org/modules_php
	 * increase post_max_size and upload_max_filesize to 10M
	 * debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
	 *
	 * @package app.Controller.Component
	 */
	class FileuploaderComponent extends Component
    {
        /**
		 * @var string
		 */
		protected $_modeleStockage = 'Fichiermodule';

        /**
		 * Le nom du modèle au sens CakePHP auquel les fichiers sont liés
		 * @var string
		 */
		protected $_colonneModele = null;

		/**
		 * Initialisation du component.
		 *
		 * @param Controller $controller
		 */
        public function initialize( Controller $controller ) {
			$settings = $this->settings;
            $this->controller = $controller;

            if( isset( $settings['modeleStockage'] ) ) {
                $this->_modeleStockage = $settings['modeleStockage'];
            }

            if( isset( $settings['colonneModele'] ) ) {
                $this->_colonneModele = $settings['colonneModele'];
            }
            else {
                $this->_colonneModele = $this->controller->modelClass;
            }
        }

		/**
		 * Retourne le nom du répertoire teporaire où sont stockés les fichiers
		 * en attente d'enregistrement.
		 *
		 * @param string $action
		 * @param integer $id
		 * @return string
		 */
        public function dirFichiersModule( $action, $id ){
            return APP.'tmp'.DS.'files'.DS.session_id().DS.$this->_colonneModele.DS.$action.DS.$id;
        }

		/**
		 * Sauvegarde des fichiers temporaires liés en base et suppression des
		 * données temporaires.
		 *
		 * @param string $dir
		 * @param boolean $delete
		 * @param integer $id
		 * @return boolean
		 */
        public function saveFichiers( $dir, $delete = false, $id ){
            $oFolder = new Folder( $dir, true, 0777 );
            $saved = true;

            if( $delete ){
                $saved = ClassRegistry::init( $this->_modeleStockage )->deleteAll(
                    array(
                        "{$this->_modeleStockage}.fk_value" => $id,
                        "{$this->_modeleStockage}.modele" => $this->_colonneModele
                    ),
                    false
                ) && $saved;
            }

            // Enregistrement des fichiers si besoin
            else {
                $files = $oFolder->find();
                if( !empty( $files ) ) {
                    foreach( $files as $file ) {
                        // Recherche de l'ancien enregistrement s'il existe
                        $oldrecord = array(
                            "{$this->_modeleStockage}" => array(
                                'name' => $file,
                                'fk_value' => $id,
                                'mime' => mime_content_type( $dir.DS.$file  ),
                                'modele' => $this->_colonneModele
                            )
                        );
                        $oldrecord = ClassRegistry::init( $this->_modeleStockage )->find( 'first', array( 'conditions' => Set::flatten( $oldrecord ) ) );

                        $record = array(
                            "{$this->_modeleStockage}" => array(
                                'name' => $file,
                                'fk_value' => $id,
                                'mime' => mime_content_type( $dir.DS.$file  ),
                                'document' => file_get_contents( $dir.DS.$file ),
                                'modele' => $this->_colonneModele
                            )
                        );
                        $record = Set::merge( $oldrecord, $record );

                        ClassRegistry::init( $this->_modeleStockage )->create( $record );

                        if( $tmpSaved = ClassRegistry::init( $this->_modeleStockage )->save() ) {
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

            return $saved;
        }

		/**
		 * Suppression des fichiers temporaire en cas d'annulation du formulaire.
		 *
		 */
        public function deleteDir(){
            $dir = $this->dirFichiersModule( $this->controller->action, $id );
            $oFolder = new Folder( $dir, true, 0777 );
            $oFolder->delete( $dir );
        }

		/**
		 * Permet d'obtenir la liste complète des fichiers liés à un enregistremennt,
		 * qu'ils soient sur disque ou en base.
		 *
		 * @param integer $id
		 * @return array
		 */
        public function fichiers( $id ){
            $fichiers = array();
            if($this->controller->action == 'edit' ){
                $fichiers = $this->_fichiersEnBase( $id );
            }
            $fichiers = Set::merge( $fichiers, $this->_fichiersSurDisque( $id ) );
            return $fichiers;
        }

		/**
         * Récupération des fichiers chargés sur le disque mais non encore envoyé sur le serveur.
         * Permet de conserver les fichiers chargés dans le cas où le formulaire nous renvoie une erreur.
		 *
		 * @param integer $id L'enregistrement auquel ces fichiers sont liés.
		 * @return array
		 */
        public function _fichiersSurDisque($id){
            $fichiers = array();

            $dir = $this->dirFichiersModule( $this->controller->action, $id );
            $oFolder = new Folder( $dir, true, 0777 );
            $files = $oFolder->find();
            if( !empty( $files ) ) {
                foreach( $files as $file ) {
                    $found = false;
                    if( !empty( $fichiers ) ) {
                        foreach( $fichiers as $i => $oldfile ) {
                            if( $oldfile == $file ) {
                                $found = $i;
                            }
                        }
                    }

                    if( $found !== false ) {
                        $fichiers[$found] = $file;
                    }
                    else {
                        $fichiers[] = $file;
                    }
                }
            }
            return $fichiers;
        }

		/**
		 * Récupération des fichiers en base.
		 *
		 * @param integer $id L'enregistrement auquel ces fichiers sont liés.
		 * @return array
		 */
        public function _fichiersEnBase( $id ) {
            $fichiers = array();

            $tmpFiles = ClassRegistry::init( $this->_modeleStockage)->find(
                'all',
                array(
                    'fields' => array( 'name' ),
                    'conditions' => array(
                        "{$this->_modeleStockage}.fk_value" => $id,
                        "{$this->_modeleStockage}.modele" => $this->_colonneModele
                    ),
                    'contain' => false
                )
            );

            $fichiers = Set::extract( $tmpFiles, "/{$this->_modeleStockage}/name" );

            return $fichiers;
        }

		/**
		 *
		 */
        public function ajaxfileupload() {
            $error = false;

            $dir = $this->dirFichiersModule( $this->controller->params['url']['action'], $this->controller->params['url']['primaryKey'] );
            $path = $dir.DS.$this->controller->params['url']['qqfile'];

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

            Configure::write( 'debug', 0 );
            $this->controller->layout = false;
            echo htmlspecialchars( json_encode( ( empty( $error ) ? array( 'success' => true ) : array( 'error' => $error ) ) ), ENT_NOQUOTES );
            die();
        }

		/**
		 * TODO: traiter les valeurs de retour
		 *
		 */
        public function ajaxfiledelete() {
            $dir = $this->dirFichiersModule( $this->controller->params['pass'][0], $this->controller->params['pass'][1] );
            $path = $dir.DS.$this->controller->params['pass'][2];
            $error = false;

            if( file_exists( $path ) ) {
                $error = !@unlink( $path );
            }

            if( $this->controller->params['pass'][0] == 'edit' ) { // Suppression d'un document se trouvant déjà enregistré -> SSI c'est un edit
                $record = ClassRegistry::init( $this->_modeleStockage)->find(
                    'first',
                    array(
                        'fields' => array( 'id' ),
                        'conditions' => array(
                            'fk_value' => $this->controller->params['pass'][1],
                            'name' => $this->controller->params['pass'][2]
                        )
                    )
                );
                if( !empty( $record ) ) {
                    $error = !ClassRegistry::init( $this->_modeleStockage)->delete( $record[$this->_modeleStockage]['id'] ) && $error;
                }
            }

            Configure::write( 'debug', false );
            $this->layout = false;
            echo htmlspecialchars( json_encode( ( empty( $error ) ? array( 'success' => true ) : array( 'error' => $error ) ) ), ENT_NOQUOTES );
            die();
        }

		/**
		 * Visualisation d'un fichier lié avant son envoi sur le serveur.
		 *
		 * @param integer $id
		 */
        public function fileview( $id ) {
            $dir = $this->dirFichiersModule( $this->controller->params['pass'][0], $this->controller->params['pass'][1] );

            $path = $dir.DS.$this->controller->params['pass'][2];

            $file = array();

            if( file_exists( $path ) ) {
                $file = array(
                    'name' => basename( $path ),
                    'mime' => mime_content_type( $path ),
                    'document' => file_get_contents( $path )/*,
                    'length' => filesize( $path )*/
                );
            }
            else if( $this->controller->params['pass'][0] == 'edit' ) {
                $record = ClassRegistry::init( $this->_modeleStockage)->find(
                    'first',
                    array(
                        'conditions' => array(
                            'fk_value' => $this->controller->params['pass'][1],
                            'name' => $this->controller->params['pass'][2]
                        ),
                        'recursive' => -1,
                        'contain' => false
                    )
                );
                if( !empty( $record ) ) {
                    $file = $record[$this->_modeleStockage];
                    if( empty( $file['document'] ) && !empty( $file['cmspath'] ) ) {
                        $cmisFile = Cmis::read( $file['cmspath'], true );
                        $file['document'] = $cmisFile['content'];
                    }
                    $file['length'] = strlen( $file['document'] );
                }
            }

            $this->controller->assert( !empty( $file ), 'error404' );
            $this->controller->assert( !empty( $file['document'] ), 'error404' );

            Configure::write( 'debug', 0 );
            $this->controller->layout = false;

            $file['length'] = strlen( $file['document'] );

            header( "Content-type: {$file['mime']}" );
            header( "Content-Length: {$file['length']}" );
            header( "Content-Disposition: attachment; filename=\"{$file['name']}\"" );

            echo $file['document'];
            die();
        }

		/**
		 * Téléchargement d'un fichier lié au module.
		 *
		 * @param integer $id
		 */
        public function download( $id ) {
            $item = ClassRegistry::init( $this->_modeleStockage)->find( 'first', array( 'conditions' => array( "{$this->_modeleStockage}.id" =>  $id) ) );

            $this->controller->assert( !empty( $item ), 'invalidParameter' );

            Configure::write( 'debug', false );
            $this->layout = false;

            if( !empty( $item[$this->_modeleStockage]['cmspath'] )  ) {
                $document = Cmis::read( $item[$this->_modeleStockage]['cmspath'], true );
                header( "Content-type: {$item[$this->_modeleStockage]['mime']}" );
                header( 'Content-Length: '.strlen( $document['content'] ) ); // FIXME: length
                header( "Content-Disposition: attachment; filename=\"{$item[$this->_modeleStockage]['name']}\"" );

                echo $document['content'];
                die();
            }
            else if( !empty( $item[$this->_modeleStockage]['document'] ) ) {
                header( "Content-type: {$item[$this->_modeleStockage]['mime']}" );
                header( 'Content-Length: '.strlen( $item[$this->_modeleStockage]['document'] ) ); // FIXME: length
                header( "Content-Disposition: attachment; filename=\"{$item[$this->_modeleStockage]['name']}\"" );

                echo $item[$this->_modeleStockage]['document'];
                die();
            }
            else {
                $this->cakeError( 'error500' );
            }
        }

		/**
		 * @param Controller $controller Controller with components to beforeRedirect
		 * @param string|array $url Either the string or url array that is being redirected to.
		 * @param integer $status The status code of the redirect
		 * @param boolean $exit Will the script exit.
		 * @return array|null Either an array or null.
		 */
		public function beforeRedirect( Controller $controller, $url, $status = null, $exit = true ) {
            parent::beforeRedirect( $controller, $url, $status , $exit );
        }
    }
?>