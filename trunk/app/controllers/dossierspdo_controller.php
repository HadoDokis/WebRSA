<?php

    class DossierspdoController extends AppController{

        var $name = 'Dossierspdo';
        var $uses = array( 'Dossierpdo', 'Detaildroitrsa', 'Situationdossierrsa', 'Option', 'Avispcgpersonne', 'Derogation' );

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'avisdero', $this->Option->avisdero() );
            $this->set( 'typdero', $this->Option->typdero() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
        }


        function index( $dossier_rsa_id = null ){
            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $conditions = array( 'Foyer.dossier_rsa_id' => $dossier_rsa_id );

            if( $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) {
                $mesCodesInsee = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? array_values( $mesCodesInsee ) : array() );
                $conditions['Adresse.numcomptt'] = $mesCodesInsee;
            }

            /// Récupération de la situation du dossier
            $options = $this->Dossierpdo->prepare( 'etat', array( 'conditions' => $conditions ) );
            $details = $this->Situationdossierrsa->find( 'first', $options );

            /// Récupération des listes des PDO
            $options = $this->Dossierpdo->prepare( 'derogation', array( 'conditions' => $conditions ) );
            $pdos = $this->Derogation->find( 'all', $options );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'pdos', $pdos );
            $this->set( 'details', $details );
        }


        function view( $derogation_id = null ){
            $this->assert( valid_int( $derogation_id ), 'invalidParameter' );

            $conditions = array( 'Derogation.id' => $derogation_id );

            $options = $this->Dossierpdo->prepare( 'derogation', array( 'conditions' => $conditions ) );
            $pdos = $this->Derogation->find( 'all', $options );


// debug( $pdos);

            $this->set( 'pdos', $pdos );
            $this->set( 'dossier_rsa_id', $this->Derogation->dossierId( $derogation_id ) );
        }


        function _add_edit( $dossier_rsa_id = null ){
                        // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $dossier_rsa_id = $this->Dossier->findById( $dossier_rsa_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );

            $this->Dossier->begin();

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Dossier->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Dossier->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Dossier->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Dossier->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
//                         $this->redirect( array(  'controller' => 'dossierspdo','action' => 'index', $this->data['Derogation']['id'] ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            // Afficage des données
            else {

            }

            $this->Dossier->commit();
            $this->render( $this->action, null, 'add_edit' );
            debug( $this->action );
        }
    }

?>