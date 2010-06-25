<?php

    class PropospdosController extends AppController{

        var $name = 'Propospdos';
        var $uses = array( 'Propopdo', 'Situationdossierrsa', 'Option', 'Propopdo', 'Typepdo', 'Typenotifpdo', 'Decisionpdo', 'Suiviinstruction', 'Piecepdo', 'Traitementpdo', 'Originepdo',  'Statutpdo', 'Statutdecisionpdo', 'Situationpdo', 'Referent' );

        var $helpers = array( 'Default' );


        protected function _setOptions() {
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motifpdo', $this->Option->motifpdo() );
            $this->set( 'categoriegeneral', $this->Option->sect_acti_emp() );
            $this->set( 'categoriedetail', $this->Option->emp_occupe() );

            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
            $this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
            $this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
            $this->set( 'originepdo', $this->Originepdo->find( 'list' ) );

            $this->set( 'statutlist', $this->Statutpdo->find( 'list' ) );
            $this->set( 'situationlist', $this->Situationpdo->find( 'list' ) );
//             $this->set( 'statutdecisionlist', $this->Statutdecisionpdo->find( 'list' ) );

            $options = $this->Propopdo->allEnumLists();
//             debug($options);
            $options = Set::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
            $this->set( compact( 'options' ) );
        }


//         function beforeFilter(){
//             $return = parent::beforeFilter();
//             $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
//             $this->set( 'pieecpres', $this->Option->pieecpres() );
//             $this->set( 'commission', $this->Option->commission() );
//             $this->set( 'motidempdo', $this->Option->motidempdo() );
//             $this->set( 'motifpdo', $this->Option->motifpdo() );
//             $this->set( 'categoriegeneral', $this->Option->sect_acti_emp() );
//             $this->set( 'categoriedetail', $this->Option->emp_occupe() );
// 
//             $this->set( 'typeserins', $this->Option->typeserins() );
//             $this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
//             $this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
//             $this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
//             $this->set( 'originepdo', $this->Originepdo->find( 'list' ) );
// 
//             $this->set( 'statutlist', $this->Statutpdo->find( 'list' ) );
//             $this->set( 'situationlist', $this->Situationpdo->find( 'list' ) );
// //             $this->set( 'statutdecisionlist', $this->Statutdecisionpdo->find( 'list' ) );
// 
//             $options = $this->Propopdo->allEnumLists();
// //             debug($options);
//             $options = Set::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
//             $this->set( compact( 'options' ) );
//             return $return;
//         }

        /**
        *   Partie pour les tables de paramétrages des PDOs
        */

        function indexparams() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

        }


        function index( $personne_id = null ){

            $nbrPersonnes = $this->Propopdo->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

            $conditions = array( 'Propopdo.personne_id' => $personne_id );

            /// Récupération de la situation du dossier
//             $options = $this->Propopdo->prepare( 'etat', array( 'conditions' => $conditions ) );
//             $details = $this->Situationdossierrsa->find( 'first', $options );

            /// Récupération des listes des PDO
            $options = $this->Propopdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );
            $pdos = $this->Propopdo->find( 'all', $options );

// debug($pdos);
            if( !empty( $pdos ) ){

                /// Récupération des Pièces liées à la PDO
                $piecespdos = $this->Piecepdo->find( 'all', array( 'conditions' => array( 'Piecepdo.propopdo_id' => Set::extract( $pdos, '/Propopdo/id' )  ), 'order' => 'Piecepdo.dateajout DESC' ) );

                $this->set( 'piecespdos', $piecespdos );
            }

            $this->set( 'personne_id', $personne_id );
            $this->_setOptions();
            $this->set( 'pdos', $pdos );

        }


        function view( $pdo_id = null ) {
            $this->assert( valid_int( $pdo_id ), 'invalidParameter' );

            $conditions = array( 'Propopdo.id' => $pdo_id );

            $options = $this->Propopdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );
            $pdo = $this->Propopdo->find( 'first', $options );

            $this->set( 'pdo', $pdo );
            $this->_setOptions();
            $this->set( 'personne_id', $pdo['Propopdo']['personne_id'] );
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
                    $id = $this->Propopdo->field( 'personne_id', array( 'id' => $id ) );
                }
                $this->redirect( array( 'action' => 'index', $id ) );

            }

//             $step = 0;
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );

//                 $nbrDossiers = $this->Dossier->find( 'count', array( 'conditions' => array( 'Dossier.id' => $personne_id ), 'recursive' => -1 ) );
//                 debug($nbrDossiers);
//                 $this->assert( ( $nbrDossiers == 0 ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $pdo_id = $id;
                $pdo = $this->Propopdo->findById( $pdo_id, null, null, 1 );

                $this->assert( !empty( $pdo ), 'invalidParameter' );
                $personne_id = Set::classicExtract( $pdo, 'Propopdo.personne_id' );
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
//                 $step ++;
            }

            $this->Dossier->Suiviinstruction->order = 'Suiviinstruction.id DESC';
            $dossier = $this->Dossier->findById( $personne_id, null, null, -1 );
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
            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );
            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->PersonneReferent->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            $this->set( 'referents', $this->Referent->find( 'list' ) );
            
            /**
            *   DEBUT: pour état du dossier PDO
            */
//             $step = null;
//             $step = Set::classicExtract( $this->data, 'Propopdo.etatdossierpdo' );
//             $step = 0;
//             switch( $step ) {
//                 case '0':
//                     $etatpdo = 'En attente d\'instruction';
//                     break;
//                 case '1':
//                     $etatpdo = 'Instruction en cours';
//                     break;
//                 case '2':
//                     $etatpdo = 'En attente de validation';
//                     break;
//                 case '3':
//                     $etatpdo = 'Décision validée';
//                     break;
//                 case '4':
//                     $etatpdo = 'Dossier traité ou En attente de pièces';
//                     break;
//                 default:
//                     $etatpdo = 'En attente d\'instruction';
//                     break;
//             }
//             $this->set( compact( 'etatpdo', 'step' ) );
            /**
            *   FIN
            */
            
            //Essai de sauvegarde
            if( !empty( $this->data ) ) {
            
//                 $this->data['Propopdo']['etatdossierpdo'] = $step + 1;
            
            
            
                // Nettoyage des Dsp
                $keys = array_keys( $this->Propopdo->schema() );
                $defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
                unset( $defaults['id'] );

                $this->data['Propopdo'] = Set::merge( $defaults, $this->data['Propopdo'] );

                if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Propopdo->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'propospdos','action' => 'index', $personne_id ) );
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
                // $this->Propopdo->findByDossierRsaId( $personne_id, null, null, -1 );

//                 if( $this->action == 'add' ) {
//                     $this->assert( empty( $this->data ), 'invalidParameter' );
//                 }
//                 else if( $this->action == 'edit' ) {
//                     $this->assert( !empty( $this->data ), 'invalidParameter' );
//                 }
            }
            $this->Propopdo->commit();

            $this->set( 'personne_id', $personne_id );
            $this->_setOptions();
            $this->render( $this->action, null, 'add_edit_'.Configure::read( 'nom_form_pdo_cg' ) );
        }
    }

?>