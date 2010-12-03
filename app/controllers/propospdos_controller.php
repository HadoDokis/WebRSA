<?php

    class PropospdosController extends AppController{

        var $name = 'Propospdos';
        var $uses = array( 'Propopdo', 'Situationdossierrsa', 'Option', 'Typepdo', 'Typenotifpdo', 'Decisionpdo', 'Suiviinstruction', 'Piecepdo',  'Traitementpdo', 'Originepdo',  'Statutpdo', 'Statutdecisionpdo', 'Situationpdo', 'Referent', 'Personne', 'Dossier' );

        var $aucunDroit = array( 'ajaxstruct', 'ajaxetatpdo', 'ajaxetat1', 'ajaxetat2', 'ajaxetat3', 'ajaxetat4', 'ajaxetat5', 'ajaxfichecalcul' );

        var $helpers = array( 'Default', 'Ajax' );

		var $commeDroit = array(
			'view' => 'Propospdos:index',
			'add' => 'Propospdos:edit'
		);

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
            $this->set( 'serviceinstructeur', $this->Propopdo->Serviceinstructeur->listOptions() );
            $this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );
//             $this->set( 'statutdecisionlist', $this->Statutdecisionpdo->find( 'list' ) );
            $this->set( 'gestionnaire', $this->User->find(
                    'list',
                    array(
                        'fields' => array(
                            'User.nom_complet'
                        ),
                        'conditions' => array(
                            'User.isgestionnaire' => 'O'
                        )
                    )
                )
            );

            $options = $this->Propopdo->allEnumLists();
// ($options);
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

           function ajaxstruct( $structurereferente_id = null ) {

                $dataStructurereferente_id = Set::extract( $this->data, 'Propopdo.structurereferente_id' );
                $structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

                $struct = $this->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );

                $this->set( 'struct', $struct );

                Configure::write( 'debug', 0 );
                $this->render( 'ajaxstruct', 'ajax' );
           }


//         function ajaxfichecalcul( $iscomplet = null ) {
//
//             $dataIscomplet = Set::extract( $this->data, 'Propopdo.iscomplet' );
//             $iscomplet = ( empty( $iscomplet ) && !empty( $dataIscomplet ) ? $dataIscomplet : $iscomplet );
//
//             Configure::write( 'debug', 0 );
//             $this->render( 'ajaxfichecalcul', 'ajax' );
//         }

        function ajaxetat1( $typepdo_id = null ) {

            $dataTypepdo_id = Set::extract( $this->data, 'Propopdo.typepdo_id' );
            $typepdo_id = ( empty( $typepdo_id ) && !empty( $dataTypepdo_id ) ? $dataTypepdo_id : $typepdo_id );
            $this->Propopdo->etatPdo( $this->data );
            $this->set( 'typepdo_id', $typepdo_id );
            Configure::write( 'debug', 0 );
            $this->render( 'ajaxetat1', 'ajax' );
        }

        function ajaxetat2( $iscomplet = null ) {
            $dataIscomplet = Set::extract( $this->data, 'Propopdo.iscomplet' );

            $iscomplet = ( empty( $iscomplet ) && !empty( $dataIscomplet ) ? $dataIscomplet : $iscomplet );


            $this->set( 'iscomplet', $iscomplet );
            Configure::write( 'debug', 0 );
            $this->render( 'ajaxetat2', 'ajax' );
        }

        function ajaxetat3( $decisionpdo_id = null ) {
            $dataDecisionpdo_id = Set::extract( $this->data, 'Propopdo.decisionpdo_id' );
            $decisionpdo_id = ( empty( $decisionpdo_id ) && !empty( $dataDecisionpdo_id ) ? $dataDecisionpdo_id : $decisionpdo_id );
//             $this->Propopdo->etatPdo( $this->data );
            $this->set( 'decisionpdo_id', $decisionpdo_id );
            /*
            if( $this->action == 'add' ) {
                $value = Set::extract( $this->data, 'Propopdo.decisionpdo_id' );
            }

            $this->set( 'value', $value );*/
            Configure::write( 'debug', 0 );
            $this->render( 'ajaxetat3', 'ajax' );
        }

        function ajaxetat4( $isvalidation = null ) {
            $dataIsvalidation = Set::extract( $this->data, 'Propopdo.isvalidation' );
            $isvalidation = ( empty( $isvalidation ) && !empty( $dataIsvalidation ) ? $dataIsvalidation : $isvalidation );
            $this->set( 'isvalidation', $isvalidation );

            Configure::write( 'debug', 0 );
            $this->render( 'ajaxetat4', 'ajax' );
        }

        function ajaxetat5( $isdecisionop = null ) {

            $dataIsdecisionop = Set::extract( $this->data, 'Propopdo.isdecisionop' );
            $isdecisionop = ( empty( $isdecisionop ) && !empty( $dataIsdecisionop ) ? $dataIsdecisionop : $isdecisionop );
            $this->set( 'isdecisionop', $isdecisionop );
            Configure::write( 'debug', 0 );
            $this->render( 'ajaxetat5', 'ajax' );
        }



        function ajaxetatpdo( $typepdo_id = null, $iscomplet = null, $decisionpdo_id = null, $isvalidation = null, $isdecisionop = null ) {

            //Etat 1
            $dataTypepdo_id = Set::extract( $this->data, 'Propopdo.typepdo_id' );
            $typepdo_id = ( empty( $typepdo_id ) && !empty( $dataTypepdo_id ) ? $dataTypepdo_id : $typepdo_id );
            $this->set( 'typepdo_id', $typepdo_id );
            //Etat 2
            $dataIscomplet = Set::extract( $this->data, 'Propopdo.iscomplet' );
            $iscomplet = ( empty( $iscomplet ) && !empty( $dataIscomplet ) ? $dataIscomplet : $iscomplet );
            $this->set( 'iscomplet', $iscomplet );
            //Etat 3
            $dataDecisionpdo_id = Set::extract( $this->data, 'Propopdo.decisionpdo_id' );
            $decisionpdo_id = ( empty( $decisionpdo_id ) && !empty( $dataDecisionpdo_id ) ? $dataDecisionpdo_id : $decisionpdo_id );
            $this->set( 'decisionpdo_id', $decisionpdo_id );
            //Etat 4
            $dataIsvalidation = Set::extract( $this->data, 'Propopdo.validationdecision' );
            $isvalidation = ( empty( $isvalidation ) && !empty( $dataIsvalidation ) ? $dataIsvalidation : $isvalidation );
            $this->set( 'isvalidation', $isvalidation );

            //Etat 5
            $dataIsdecisionop = Set::extract( $this->data, 'Propopdo.isdecisionop' );
            $isdecisionop = ( empty( $isdecisionop ) && !empty( $dataIsdecisionop ) ? $dataIsdecisionop : $isdecisionop );
            $this->set( 'isdecisionop', $isdecisionop );

            $this->Propopdo->etatPdo( $this->data );
            Configure::write( 'debug', 0 );
            $this->render( 'ajaxetatpdo', 'ajax' );
        }


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

//             $freu = array(
//                 'Propopdo' => array(
//                     'personne_id' => 185587,
//                     'typepdo_id' => 1,
//                     'choixpdo' => 'JUS',
//                     'originepdo_id' => 1,
//                 )
//             );
//             $this->Propopdo->begin();
//             $this->Propopdo->create( $freu );
//             if( !$this->Propopdo->save(  ) ) {
//                 debug($this->Propopdo->validationErrors);
//             }
//             $this->Propopdo->rollback();


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
                $dossier_id = $this->Personne->dossierId( $personne_id );

//                 $nbrDossiers = $this->Dossier->find( 'count', array( 'conditions' => array( 'Dossier.id' => $personne_id ), 'recursive' => -1 ) );
//                 debug($nbrDossiers);
//                 $this->assert( ( $nbrDossiers == 0 ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $pdo_id = $id;
                $pdo = $this->Propopdo->findById( $pdo_id, null, null, 1 );

                $this->assert( !empty( $pdo ), 'invalidParameter' );
                $personne_id = Set::classicExtract( $pdo, 'Propopdo.personne_id' );
                $dossier_id = $this->Personne->dossierId( $personne_id );
//                 $step ++;
            }

            $this->Dossier->Suiviinstruction->order = 'Suiviinstruction.id DESC';

            $dossier = $this->Dossier->findById( $personne_id, null, null, -1 );
            // Recherche de la dernière entrée des suivis instruction  associée au dossier
            $suiviinstruction = $this->Dossier->Suiviinstruction->find(
                'first',
                array(
                    'conditions' => array( 'Suiviinstruction.dossier_id' => $dossier_id ),
                    'order' => array( 'Suiviinstruction.date_etat_instruction DESC' ),
                    'recursive' => -1
                )
            );
            $dossier = Set::merge( $dossier, $suiviinstruction );
            $this->set( compact( 'dossier' ) );

            $this->Propopdo->begin();
            $dossier_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->PersonneReferent->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            $this->set( 'referents', $this->Referent->find( 'list' ) );

            /**
            *   FIN
            */
            //Essai de sauvegarde
            if( !empty( $this->data ) ) {

// debug($this->data);
                //FIXME: faire une fonction
//                 $defaults = $this->Propopdo->nullify( array( 'exceptions' => 'id' ) );
//                 debug( $defaults );
//                 die();
                // Nettoyage des Propopdos
                $keys = array_keys( $this->Propopdo->schema() );
                $defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
                unset( $defaults['id'] );

                $this->data['Propopdo'] = Set::merge( $defaults, $this->data['Propopdo'] );

                if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Propopdo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Propopdo->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'propospdos','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
                else {
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
            }
            //Affichage des données
            else {
                if( $this->action == 'edit' ) {
                    $this->data = $pdo;

                }
                // $this->Propopdo->findByDossierId( $personne_id, null, null, -1 );

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
			$this->set( 'structs', $this->Propopdo->Structurereferente->find( 'list' ) );
            $this->render( $this->action, null, 'add_edit_'.Configure::read( 'nom_form_pdo_cg' ) );
        }
    }
?>
