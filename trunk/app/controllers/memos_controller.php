<?php
    class MemosController extends AppController
    {

        var $name = 'Memos';
        var $uses = array( 'Memo', 'Option', 'Personne' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );



        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){

            $nbrPersonnes = $this->Memo->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

            $memos = $this->Memo->find(
                'all',
                array(
                    'conditions' => array(
                        'Memo.personne_id' => $personne_id
                    ),
                    'recursive' => -1
                )
            );

            $this->set( 'memos', $memos );

            $this->set( 'personne_id', $personne_id );
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

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $id ) );
            }

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $memo_id = $id;
                $memo = $this->Memo->findById( $memo_id, null, null, -1 );
                $this->assert( !empty( $memo ), 'invalidParameter' );

                $personne_id = $memo['Memo']['personne_id'];
//                 $dossier_rsa_id = $this->Memo->dossierId( $memo_id );
            }

            $this->Memo->begin();

            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Memo->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            if( !empty( $this->data ) ){

                if( $this->Memo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Memo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Memo->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'memos','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $memo;
                }
            }
            $this->Memo->commit();


            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        */

        public function delete( $id ) {
            $this->Default->delete( $id );
        }

    }
?>