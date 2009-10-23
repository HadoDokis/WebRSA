<?php

    class DossierspdoController extends AppController{

        var $name = 'Dossierspdo';
        var $uses = array( 'Dossierpdo', 'Situationdossierrsa', 'Option', 'Propopdo', 'Typepdo', 'Decisionpdo', 'Typenotifpdo', 'Suiviinstruction', 'Piecepdo' );

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motifpdo', $this->Option->motifpdo() );
            $this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
            $this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
        }


        function index( $dossier_rsa_id = null ){
            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $conditions = array( 'Dossier.id' => $dossier_rsa_id );

            if( $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) {
                $mesCodesInsee = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? array_values( $mesCodesInsee ) : array() );
                $conditions['Adresse.numcomptt'] = $mesCodesInsee;
            }

            /// Récupération de la situation du dossier
            $options = $this->Dossierpdo->prepare( 'etat', array( 'conditions' => $conditions ) );
            $details = $this->Situationdossierrsa->find( 'first', $options );

            /// Récupération des listes des PDO
            $options = $this->Dossierpdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );
            $pdo = $this->Propopdo->find( 'first', $options );

            if( !empty( $pdo ) ){
                /// Récupération des Types de notification liées à la PDO
                $notif = $this->Typenotifpdo->find( 'all', array( 'conditions' => array( 'Typenotifpdo.id' => Set::classicExtract( $pdo, 'Propopdo.typenotifpdo_id' )  ) ) );

                /// Récupération des Pièces liées à la PDO
                $piecespdos = $this->Piecepdo->find( 'all', array( 'conditions' => array( 'Piecepdo.propopdo_id' => Set::classicExtract( $pdo, 'Propopdo.id' )  ), 'order' => 'Piecepdo.dateajout DESC' ) );
// debug($piecespdos);
                $this->set( 'notif', $notif );
                $this->set( 'piecespdos', $piecespdos );
            }

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'pdo', $pdo );

            $this->set( 'details', $details );
        }


        function view( $pdo_id = null ) {
            $this->assert( valid_int( $pdo_id ), 'invalidParameter' );

            $conditions = array( 'Propopdo.id' => $pdo_id );

            $options = $this->Dossierpdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );
            $pdo = $this->Propopdo->find( 'first', $options );

            $this->set( 'pdo', $pdo );
            $this->set( 'dossier_rsa_id', $pdo['Propopdo']['dossier_rsa_id'] );
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
            if( $this->action == 'add' ) {
                $dossier_rsa_id = $id;
                $nbrDossiers = $this->Dossier->find( 'count', array( 'conditions' => array( 'Dossier.id' => $dossier_rsa_id ), 'recursive' => -1 ) );
                $this->assert( ( $nbrDossiers == 1 ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $pdo_id = $id;
                $pdo = $this->Propopdo->findById( $pdo_id, null, null, -1 );
//                 $typenotifpdo = $this->Typenotifpdo->Propopdo->findById( $pdo_id, null, null, -1 );
                $this->assert( !empty( $pdo ), 'invalidParameter' );
                $dossier_rsa_id = Set::classicExtract( $pdo, 'Propopdo.dossier_rsa_id' );
            }

            $this->Propopdo->begin();
            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Propopdo->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );


            //Essai de sauvegarde
            if( !empty( $this->data ) ) {

                if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) /*&& $this->Typenotifpdo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) */) {
                    if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) )/* && $this->Typenotifpdo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) )*/) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Propopdo->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'dossierspdo','action' => 'index', $dossier_rsa_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            //Affichage des données
            else {
                $this->data = $this->Propopdo->findByDossierRsaId( $dossier_rsa_id, null, null, -1 );



                if( $this->action == 'add' ) {
                    $this->assert( empty( $this->data ), 'invalidParameter' );
                }
                else if( $this->action == 'edit' ) {
                    $this->assert( !empty( $this->data ), 'invalidParameter' );
                }
            }
            $this->Propopdo->commit();

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }

?>