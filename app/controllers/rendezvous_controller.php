<?php
    class RendezvousController extends AppController
    {

        var $name = 'Rendezvous';
        var $uses = array( 'Rendezvous', 'Option', 'Personne', 'Structurereferente' );
        var $helpers = array( 'Locale', 'Csv' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            // Staut RDV
            $this->set( 'statutrdv', $this->Option->statutrdv() );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $rdvs = $this->Rendezvous->find( 'all', array( 'conditions' => array( 'Rendezvous.personne_id' => $personne_id ) ) );
            $this->set( 'rdvs', $rdvs );

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


            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );
//             debug( $struct );

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $rdv_id = $id;
                $rdv = $this->Rendezvous->findById( $rdv_id, null, null, -1 );
                $this->assert( !empty( $rdv ), 'invalidParameter' );

                $personne_id = $rdv['Rendezvous']['personne_id'];
                $dossier_rsa_id = $this->Rendezvous->dossierId( $rdv_id );
            }

            $this->Rendezvous->begin();

            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Rendezvous->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            if( !empty( $this->data ) ){
// debug( $this->data );
                if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Rendezvous->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'rendezvous','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $rdv;
                }
            }
            $this->Rendezvous->commit();

            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }




        function exportcsv() {
            $params = $this->Rendezvous->search( array_multisize( $this->params['named'] ) );
            $rdvs = $this->Rendezvous->find( 'all', $params );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'rdvs' ) );
        }
    }
?>