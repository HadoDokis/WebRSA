<?php
    class ComitesexamenapresController extends AppController
    {

        var $name = 'Comitesexamenapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Comiteexamenapre' );
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

        function index( $comiteexamenapre_id = null ){
            $this->assert( valid_int( $comiteexamenapre_id ), 'invalidParameter' );

           $comiteexamenapre = $this->Comiteexamenapre->find( 'first', array( 'conditions' => array( 'Comiteexamenapre.id' => $comiteexamenapre_id ) ) );
            $this->set( 'comiteexamenapre', $comiteexamenapre );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $comiteexamenapre_id = null ){
            $comiteexamenapre = $this->Comiteexamenapre->find( 'first', array( 'conditions' => array( 'Comiteexamenapre.id' => $comiteexamenapre_id ) ) );
            $this->set( 'comiteexamenapre', $comiteexamenapre );

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
            $this->Comiteexamenapre->begin();

            /// Récupération des id afférents
            if( $this->action == 'add' ) {
                $this->assert( empty( $id ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $comiteexamenapre_id = $id;
                $comiteexamenapre = $this->Comiteexamenapre->findById( $comiteexamenapre_id, null, null, 1 );
                $this->assert( !empty( $comiteexamenapre ), 'invalidParameter' );

            }

            if( !empty( $this->data ) ){

                if( $this->Comiteexamenapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->Comiteexamenapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

                    if( $saved ) {
                        $this->Comiteexamenapre->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'comitesexamenapres','action' => 'view', $this->Comiteexamenapre->id ) );
                    }
                    else {
                        $this->Comiteexamenapre->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $comiteexamenapre;
                }
            }
            $this->Comiteexamenapre->commit();

            $this->render( $this->action, null, 'add_edit' );
        }

    }
?>