<?php

    class DossierspdoController extends AppController{

        var $name = 'Dossierspdo';
        var $uses = array( 'Dossierpdo', 'Situationdossierrsa', 'Option', 'Propopdo', 'Typepdo', 'Decisionpdo', 'Typenotifpdo', 'Suiviinstruction', 'Piecepdo', 'PropopdoTypenotifpdo', 'Originepdo',  'Statutpdo', 'Statutdecisionpdo', 'Situationpdo', 'Referent' );

        var $helpers = array( 'Default' );

        function beforeFilter(){
            $return = parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motifpdo', $this->Option->motifpdo() );
            $this->set( 'categoriegeneral', $this->Option->sect_acti_emp() );
            $this->set( 'categoriedetail', $this->Option->emp_occupe() );

            $this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
            $this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
            $this->set( 'originepdo', $this->Originepdo->find( 'list' ) );

            $this->set( 'statutlist', $this->Statutpdo->find( 'list' ) );
            $this->set( 'situationlist', $this->Situationpdo->find( 'list' ) );
            $this->set( 'statutdecisionlist', $this->Statutdecisionpdo->find( 'list' ) );

            $options = $this->Propopdo->allEnumLists();
//             debug($options);
            $options = Set::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
            $this->set( compact( 'options' ) );
            return $return;
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
            $pdos = $this->Propopdo->find( 'all', $options );

            if( !empty( $pdos ) ){
                /// Récupération des Types de notification liées à la PDO
                $notifs = $this->PropopdoTypenotifpdo->find( 'all', array( 'conditions' => array( 'PropopdoTypenotifpdo.propopdo_id' => Set::extract( $pdos, '/Propopdo/id' )  ) ) );

                /// Récupération des Pièces liées à la PDO
                $piecespdos = $this->Piecepdo->find( 'all', array( 'conditions' => array( 'Piecepdo.propopdo_id' => Set::extract( $pdos, '/Propopdo/id' )  ), 'order' => 'Piecepdo.dateajout DESC' ) );
// debug($notifs);
                $this->set( 'notifs', $notifs );
                $this->set( 'piecespdos', $piecespdos );
            }

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'pdos', $pdos );
// debug($pdos);
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
            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {

                if( $this->action == 'edit' ) {
                    $id = $this->Propopdo->field( 'dossier_rsa_id', array( 'id' => $id ) );
                }
                $this->redirect( array( 'action' => 'index', $id ) );

            }


            if( $this->action == 'add' ) {
                $dossier_rsa_id = $id;
//                 $nbrDossiers = $this->Dossier->find( 'count', array( 'conditions' => array( 'Dossier.id' => $dossier_rsa_id ), 'recursive' => -1 ) );
//                 debug($nbrDossiers);
//                 $this->assert( ( $nbrDossiers == 0 ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $pdo_id = $id;
                $pdo = $this->Propopdo->findById( $pdo_id, null, null, 1 );
//                 $typenotifpdo = $this->Typenotifpdo->Propopdo->findById( $pdo_id, null, null, -1 );
                $this->assert( !empty( $pdo ), 'invalidParameter' );
                $dossier_rsa_id = Set::classicExtract( $pdo, 'Propopdo.dossier_rsa_id' );
            }

            $this->Dossier->Suiviinstruction->order = 'Suiviinstruction.id DESC';
            $dossier = $this->Dossier->findById( $dossier_rsa_id, null, null, -1 );
            // Recherche de la dernière entrée des suivis instruction  associée au dossier
            $suiviinstruction = $this->Dossier->Suiviinstruction->find(
                'first',
                array(
                    'conditions' => array( 'Suiviinstruction.dossier_rsa_id' => $dossier_rsa_id ),
                    'order' => array( 'Suiviinstruction.date_etat_instruction DESC' ),
                    'recursive' => -1
                )
            );
            $dossier = Set::merge( $dossier, $suiviinstruction );

            $this->set( compact( 'dossier' ) );

            $this->Propopdo->begin();
            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Propopdo->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            $this->set( 'referents', $this->Referent->find( 'list' ) );
            //Essai de sauvegarde
            if( !empty( $this->data ) ) {
                // Nettoyage des Dsp
                $keys = array_keys( $this->Propopdo->schema() );
                $defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
                unset( $defaults['id'] );

                $this->data['Propopdo'] = Set::merge( $defaults, $this->data['Propopdo'] );
// debug($this->data);
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
                if( $this->action == 'edit' ) {
                    $this->data = $pdo;
                }
                // $this->Propopdo->findByDossierRsaId( $dossier_rsa_id, null, null, -1 );

//                 if( $this->action == 'add' ) {
//                     $this->assert( empty( $this->data ), 'invalidParameter' );
//                 }
//                 else if( $this->action == 'edit' ) {
//                     $this->assert( !empty( $this->data ), 'invalidParameter' );
//                 }
            }
            $this->Propopdo->commit();

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }

?>