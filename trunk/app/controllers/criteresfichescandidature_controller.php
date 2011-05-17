<?php
    /**
    * Gestion des séances d'équipes pluridisciplinaires.
    *
    * PHP versions 5
    *
    * @package       app
    * @subpackage    app.app.controllers
    */

    class CriteresfichescandidatureController extends AppController
    {
        public $helpers = array( 'Default', 'Default2', 'Ajax', 'Locale', 'Csv' );
        public $uses = array(  'Criterefichecandidature', 'ActioncandidatPersonne'/*, 'Actioncandidat' */, 'Partenaire');
        public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );
        public $aucunDroit = array( 'exportcsv' );


        /**
        *
        */
        public function _setOptions() {
            $options = array();
            $optionsactions = $this->ActioncandidatPersonne->Actioncandidat->allEnumLists();
            $actions = $this->ActioncandidatPersonne->Actioncandidat->find( 'list', array( 'fields' => array( 'name' ) ) );
            $partenaires = $this->Partenaire->find( 'list', array( 'fields' => array( 'libstruc' ) ) );
            $options = $this->ActioncandidatPersonne->allEnumLists();
            $options = Set::merge( $options, $optionsactions );
            $this->set( compact( 'actions', 'options', 'partenaires' ) );

            $this->set( 'referents', $this->ActioncandidatPersonne->Referent->find( 'list', array( 'recursive' => -1 ) ) );
        }


        /**
        *
        */

        public function index() {

            if( !empty( $this->data ) ) {
                $data = $this->data;

                $queryData = $this->Criterefichecandidature->search( $data );
                $queryData['limit'] = 10;
                $this->paginate = $queryData;
                $actionscandidats_personnes = $this->paginate( $this->ActioncandidatPersonne );

                foreach( $actionscandidats_personnes as $key => $actioncandidat_personne ) {
                    $actionscandidats_personnes[$key]['Personne']['nom_complet'] = implode(
                        ' ',
                        array(
                            @$actionscandidats_personnes[$key]['Personne']['qual'],
                            @$actionscandidats_personnes[$key]['Personne']['nom'],
                            @$actionscandidats_personnes[$key]['Personne']['prenom']
                        )
                    );
                    $actionscandidats_personnes[$key]['Referent']['nom_complet'] = implode(
                        ' ',
                        array(
                            @$actionscandidats_personnes[$key]['Referent']['qual'],
                            @$actionscandidats_personnes[$key]['Referent']['nom'],
                            @$actionscandidats_personnes[$key]['Referent']['prenom']
                        )
                    );

                }
// debug( $actionscandidats_personnes);

                $this->set( 'actionscandidats_personnes', $actionscandidats_personnes );
            }
            $this->_setOptions();
            $this->render( null, null, 'index' );
        }


        /**
        * Export du tableau en CSV
        */

        public function exportcsv() {

            $querydata = $this->Criterefichecandidature->search( array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );
            $actionscandidats_personnes = $this->ActioncandidatPersonne->find( 'all', $querydata );

            foreach( $actionscandidats_personnes as $key => $actioncandidat_personne ) {
                $actionscandidats_personnes[$key]['Personne']['nom_complet'] = implode(
                    ' ',
                    array(
                        @$actionscandidats_personnes[$key]['Personne']['qual'],
                        @$actionscandidats_personnes[$key]['Personne']['nom'],
                        @$actionscandidats_personnes[$key]['Personne']['prenom']
                    )
                );
                $actionscandidats_personnes[$key]['Referent']['nom_complet'] = implode(
                    ' ',
                    array(
                        @$actionscandidats_personnes[$key]['Referent']['qual'],
                        @$actionscandidats_personnes[$key]['Referent']['nom'],
                        @$actionscandidats_personnes[$key]['Referent']['prenom']
                    )
                );

            }

            $this->_setOptions();
            $this->layout = ''; // FIXME ?
            $this->set( compact( 'actionscandidats_personnes' ) );
        }

    }
?>