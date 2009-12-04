<?php
    class ComitesapresController extends AppController
    {

        var $name = 'Comitesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Comiteapre', 'Participantcomite', 'Apre', 'Referentapre' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'referentapre', $this->Referentapre->find( 'list' ) );
            $options = array(
                'decisioncomite' => array(
                    'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
                    'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
                    'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
                ),
                'recoursapre' => array(
                    'N' => __d( 'apre', 'ENUM::RECOURSAPRE::N', true ),
                    'O' => __d( 'apre', 'ENUM::RECOURSAPRE::O', true )
                )
            );
            $this->set( 'options', $options );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index(){

            if( !empty( $this->data ) ) {
                $this->Dossier->begin(); // Pour les jetons
                $comitesapres = $this->Comiteapre->search( $this->data );
                $comitesapres['limit'] = 10;
                $this->paginate = $comitesapres;
                $comitesapres = $this->paginate( 'Comiteapre' );

                $this->Dossier->commit();
                $this->set( 'comitesapres', $comitesapres );
            }

        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $comiteapre_id = null ){
            $this->assert( valid_int( $comiteapre_id ), 'invalidParameter' );

            $comiteapre = $this->Comiteapre->find(
                'first',
                array(
                    'conditions' => array( 'Comiteapre.id' => $comiteapre_id ),
                    'recursive' => 2
                )
            );
// debug($comiteapre);
            foreach( $comiteapre['Apre'] as $key => $apre ) {
                // Personne
                $personne = $this->Apre->Personne->findById( $apre['personne_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $personne );

                // Foyer
                $foyer = $this->Apre->Personne->Foyer->findById( $personne['Personne']['foyer_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $foyer );

                // Dossier
                $dossier = $this->Apre->Personne->Foyer->Dossier->findById( $foyer['Foyer']['dossier_rsa_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $dossier );

                // Adresse
                $adresse = $this->Apre->Personne->Foyer->Adressefoyer->Adresse->findById( $foyer['Foyer']['id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $adresse );
            }

            $this->set( 'comiteapre', $comiteapre );

            $participants = $this->Participantcomite->find( 'list' );
            $this->set( 'participants', $participants );

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
            $this->Comiteapre->begin();

            /// Récupération des id afférents
            if( $this->action == 'add' ) {
                $this->assert( empty( $id ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $comiteapre_id = $id;
                $comiteapre = $this->Comiteapre->findById( $comiteapre_id, null, null, 1 );
                $this->assert( !empty( $comiteapre ), 'invalidParameter' );

            }

            if( !empty( $this->data ) ){

                if( $this->Comiteapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->Comiteapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

                    if( $saved ) {
                        $this->Comiteapre->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'comitesapres','action' => 'view', $this->Comiteapre->id ) );
                    }
                    else {
                        $this->Comiteapre->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $comiteapre;
                }
            }
            $this->Comiteapre->commit();

            $this->render( $this->action, null, 'add_edit' );
        }

        function exportcsv() {
            $querydata = $this->Comiteapre->search( array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );
            $comitesapres = $this->Comiteapre->find( 'all', $querydata );

            $this->layout = '';
            $this->set( compact( 'comitesapres' ) );
        }

    }
?>