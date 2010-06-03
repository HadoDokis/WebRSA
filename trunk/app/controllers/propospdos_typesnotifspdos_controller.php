<?php
    class PropospdosTypesnotifspdosController extends AppController
    {

        var $name = 'PropospdosTypesnotifspdos';
        var $uses = array( 'PropopdoTypenotifpdo', 'Typenotifpdo', 'Propopdo'/*, 'Dossier'*/ );


        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
        }


        function index( $pdo_id = null ) {
            $this->assert( valid_int( $pdo_id ), 'invalidParameter' );
            $dossier_id = $this->Propopdo->dossierId( $pdo_id );

            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'dossierspdo', 'action' => 'index', $dossier_id ) );
            }


            $notifs = $this->PropopdoTypenotifpdo->find(
                'all',
                array(
                    'conditions' => array(
                        'PropopdoTypenotifpdo.propopdo_id' => $pdo_id
                    )
                )
            );
            $this->set( 'pdo_id', $pdo_id );
            $this->set( compact( 'notifs', 'dossier_id' ) );
// debug($notifs);
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

        function _add_edit( $id = null ){
            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {

                if( $this->action == 'edit' ) {
                    $id = $this->PropopdoTypenotifpdo->field( 'propopdo_id', array( 'id' => $id ) );
                }
                $this->redirect( array( 'action' => 'index', $id ) );

            }

            if( $this->action == 'add' ) {
                $pdo_id = $id;
//                 $dossier_id = $pdo_id;
//                 $nbrPdos = $this->PropopdoTypenotifpdo->find( 'count', array( 'conditions' => array( 'PropopdoTypenotifpdo.id' => $pdo_id ), 'recursive' => -1 ) );
//                 $this->assert( ( $nbrPdos == 1 ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $propotype_id = $id;
                $propotype = $this->PropopdoTypenotifpdo->findById( $propotype_id, null, null, -1 );
                $this->assert( !empty( $propotype ), 'invalidParameter' );
                $pdo_id = Set::classicExtract( $propotype, 'PropopdoTypenotifpdo.propopdo_id' );
            }

            $dossier_id = $this->Propopdo->dossierId( $pdo_id );
// debug($dossier_id);
            $this->set( 'dossier_id', $dossier_id );


            if( !empty( $this->data ) ) {
                if( $this->PropopdoTypenotifpdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'index', $id ) );
                }
            }
            else {
                if( $this->action == 'edit' ) {
                    $this->data = $propotype;
                }
                else {
                    $this->data['PropopdoTypenotifpdo']['propopdo_id'] = $pdo_id;
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>