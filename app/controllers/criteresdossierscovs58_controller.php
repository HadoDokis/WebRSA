<?php
    /**
    * Gestion des séances d'équipes pluridisciplinaires.
    *
    * PHP versions 5
    *
    * @package       app
    * @subpackage    app.app.controllers
    */

    class Criteresdossierscovs58Controller extends AppController
    {
        public $helpers = array( 'Default', 'Default2', 'Ajax', 'Locale', 'Csv' );
        public $uses = array(  'Criteredossiercov58', 'Dossiercov58' );

        public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );


        /**
        *
        */
        public function _setOptions() {
            $this->set( 'options', $this->Dossiercov58->allEnumLists() );
            $this->set( 'themes', $this->Dossiercov58->Themecov58->find( 'list' ) );
        }


        /**
        *
        */

        public function index() {
// debug($this->data);
            if( !empty( $this->data ) ) {
                $data = $this->data;

                $queryData = $this->Criteredossiercov58->search( $data );
                $queryData['limit'] = 10;
                $this->paginate = $queryData;
                $dossierscovs58 = $this->paginate( $this->Dossiercov58 );

                foreach( $dossierscovs58 as $key => $dossiercov58 ) {
                    $dossierscovs58[$key]['Personne']['nom_complet'] = implode(
                        ' ',
                        array(
                            @$dossierscovs58[$key]['Personne']['qual'],
                            @$dossierscovs58[$key]['Personne']['nom'],
                            @$dossierscovs58[$key]['Personne']['prenom']
                        )
                    );
                }


                $this->set( 'dossierscovs58', $dossierscovs58 );
            }
            $this->_setOptions();
            $this->render( null, null, 'index' );
        }

    }
?>