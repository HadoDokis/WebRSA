<?php
    class ComitesapresController extends AppController
    {

        var $name = 'Comitesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Comiteapre', 'Participantcomite', 'Apre' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();

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

            $comiteapre = $this->Comiteapre->find( 'first', array( 'conditions' => array( 'Comiteapre.id' => $comiteapre_id ) ) );
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
                        $this->redirect( array(  'controller' => 'comitesapres','action' => 'index', $this->Comiteapre->id ) );
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

    }
?>