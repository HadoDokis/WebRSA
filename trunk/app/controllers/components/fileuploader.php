<?php

    class FileuploaderComponent extends Component
    {
        /**
        *
        */

        protected $_modeleStockage = 'Fichiermodule';

        /**
        * Le nom du modèle au sens CakePHP auquel les fichiers sont liés
        */

        protected $_colonneModele = null;

        /** *******************************************************************
            The initialize method is called before the controller's beforeFilter method.
        ******************************************************************** */
        public function initialize( &$controller, $settings = array() ) {
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
        *
        */

        public function dirFichiersModule( $action, $id, $type ){
//         debug( $this->_colonneModele );
            return APP.'tmp'.DS.'files'.DS.session_id().DS.$this->_colonneModele.DS.$action.DS.$id.DS.$type;
        }


        public function saveFichiers( $dir, $delete = false ){
            $oFolder = new Folder( $dir, true, 0777 );
            $saved = true;

            if( $delete ){
                $saved = ClassRegistry::init( $this->_modeleStockage )->deleteAll(
                    array(
                        "{$this->_modeleStockage}.fk_value" => $this->controller->{$this->_colonneModele}->id,
                        "{$this->_modeleStockage}.modele" => $this->_colonneModele
                    )
                ) && $saved;
            }
//             // Enregistrement des fichiers si besoin
            else {
                $files = $oFolder->find();
                if( !empty( $files ) ) {
                    foreach( $files as $file ) {
                        // Recherche de l'ancien enregistrement s'il existe
                        $oldrecord = array(
                            "{$this->_modeleStockage}" => array(
                                'name' => $file,
                                'fk_value' => $this->controller->{$this->_colonneModele}->id,
                                'mime' => mime_content_type( $dir.DS.$file  ),
                                'modele' => $this->_colonneModele
                            )
                        );
                        $oldrecord = ClassRegistry::init( $this->_modeleStockage )->find( 'first', array( 'conditions' => Set::flatten( $oldrecord ) ) );

                        $record = array(
                            "{$this->_modeleStockage}" => array(
                                'name' => $file,
                                'fk_value' => $this->controller->{$this->_colonneModele}->id,
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
        *
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
                header( "Content-Disposition: attachment; filename={$item[$this->_modeleStockage]['name']}" );

                echo $document['content'];
                die();
            }
            else if( !empty( $item[$this->_modeleStockage]['document'] ) ) {
                header( "Content-type: {$item[$this->_modeleStockage]['mime']}" );
                header( 'Content-Length: '.strlen( $item[$this->_modeleStockage]['document'] ) ); // FIXME: length
                header( "Content-Disposition: attachment; filename={$item[$this->_modeleStockage]['name']}" );

                echo $item[$this->_modeleStockage]['document'];
                die();
            }
            else {
                $this->cakeError( 'error500' );
            }
        }


        /** *******************************************************************
            The beforeRedirect method is invoked when the controller's redirect method
            is called but before any further action. If this method returns false the
            controller will not continue on to redirect the request.
            The $url, $status and $exit variables have same meaning as for the controller's method.
        ******************************************************************** */
        public function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
            parent::beforeRedirect( $controller, $url, $status , $exit );
        }
    }
?>