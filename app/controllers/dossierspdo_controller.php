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
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motideccg', $this->Option->motideccg() );
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

            /// Calcul du numéro du PDO
            $nbPDO = $this->Derogation->find(
                'count',
                array(
                    'conditions' => array(
                        'Derogation.avispcgpersonne_id' => $pdos[0]['Avispcgpersonne']['id']
                    ),
                    'order' => 'Derogation.ddavisdero DESC'
                )
            );
            $this->set( 'nbPDO', $nbPDO );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'pdos', $pdos );
            $this->set( 'details', $details );
        }


        function view( $derogation_id = null ){
            $this->assert( valid_int( $derogation_id ), 'invalidParameter' );

            $conditions = array( 'Derogation.id' => $derogation_id );

            $options = $this->Dossierpdo->prepare( 'derogation', array( 'conditions' => $conditions ) );
            $pdos = $this->Derogation->find( 'all', $options );

            $this->set( 'pdos', $pdos );
            $this->set( 'dossier_rsa_id', $this->Derogation->dossierId( $derogation_id ) );
        }


        function _add_edit( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'invalidParameter' );

            if( $this->action == 'add' ) {
                $dossier_rsa_id = $id;
                $dossier_rsa = $this->Dossier->findById( $dossier_rsa_id );
                $this->assert( !empty( $dossier_rsa ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $derogation_id = $id;
                $dossier_rsa_id = $this->Derogation->dossierId( $derogation_id );
                $dossier_rsa = $this->Dossier->findById( $dossier_rsa_id );
                $this->assert( !empty( $dossier_rsa ), 'invalidParameter' );
            }

//              debug( $this->data );

            $this->Derogation->begin();

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Derogation->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Derogation->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Derogation->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Derogation->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'dossierspdo','action' => 'index', $dossier_rsa_id/*$this->data['Derogation']['id']*/ ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            // Affichage des données
            else if( $this->action == 'add' ) {
                $avispcgpersonne_id = $this->Avispcgpersonne->idFromDossierId( $dossier_rsa_id );
                $this->assert( !empty( $avispcgpersonne_id ), 'invalidParameter' );
                $this->data['Derogation']['avispcgpersonne_id'] = $avispcgpersonne_id;
            }
            else if( $this->action == 'edit' ) {
                // ...
                $derogation = $this->Derogation->findById( $id, null, null, -1 );
                $this->assert( !empty( $derogation ), 'invalidParameter' );

                // Assignation au formulaire
                $this->data = $derogation;
            }
            $this->Derogation->commit();

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }

?>