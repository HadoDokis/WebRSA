<?php
    /**
    * Gestion des séances d'équipes pluridisciplinaires.
    *
    * PHP versions 5
    *
    * @package       app
    * @subpackage    app.app.controllers
    */

    class Criteresbilansparcours66Controller extends AppController
    {
        public $helpers = array( 'Default', 'Default2', 'Ajax', 'Locale' );
        public $uses = array(  'Criterebilanparcours66', 'Bilanparcours66', 'Option', 'Referent' );
        public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );


        /**
        *
        */
        public function _setOptions() {
            $this->set( 'options', $this->Bilanparcours66->allEnumLists() );
            $this->set( 'struct', $this->Bilanparcours66->Referent->Structurereferente->listOptions() );
            $this->set( 'referents', $this->Bilanparcours66->Referent->listOptions() );
        }


        /**
        *
        */

        public function index() {

            if( !empty( $this->data ) ) {
                $data = $this->data;
// 
                if( !empty( $data['Bilanparcours66']['referent_id'] )) {
                    $referentId = suffix( $data['Bilanparcours66']['referent_id'] );
                    $data['Bilanparcours66']['referent_id'] = $referentId;
                }

                $queryData = $this->Criterebilanparcours66->search( $data );
                $queryData['limit'] = 10;
                $this->paginate = $queryData;
                $bilansparcours66 = $this->paginate( $this->Bilanparcours66 );

                foreach( $bilansparcours66 as $key => $bilanparcours66 ) {
                    $bilansparcours66[$key]['Personne']['nom_complet'] = implode(
                        ' ',
                        array(
                            @$bilansparcours66[$key]['Orientstruct']['Personne']['qual'],
                            @$bilansparcours66[$key]['Orientstruct']['Personne']['nom'],
                            @$bilansparcours66[$key]['Orientstruct']['Personne']['prenom']
                        )
                    );
                    $bilansparcours66[$key]['Referent']['nom_complet'] = implode(
                        ' ',
                        array(
                            @$bilansparcours66[$key]['Referent']['qual'],
                            @$bilansparcours66[$key]['Referent']['nom'],
                            @$bilansparcours66[$key]['Referent']['prenom']
                        )
                    );

                }


                $this->set( 'bilansparcours66', $bilansparcours66 );
            }
            $this->_setOptions();
            $this->render( null, null, 'index' );
        }


    }
?>