<?php

    class CuisController extends AppController {

        var $name = 'Cuis';
        var $uses = array( 'Cui', 'Option', 'Referent', 'Personne', 'Dossier', 'Structurereferente', 'Dsp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Adressefoyer', 'AdresseFoyer', 'Detaildroitrsa', 'Infofinanciere', 'Detailcalculdroitrsa', 'Departement' );

        var $helpers = array( 'Default', 'Locale', 'Csv', 'Ajax', 'Xform' );
        var $components = array( 'RequestHandler' );
//         var $aucunDroit = array( 'ajax' );

        /** ********************************************************************
        *
        *** *******************************************************************/
        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();
            $options = $this->Cui->allEnumLists();
            $typevoie = $this->Option->typevoie();
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'nationalite', $this->Option->nationalite() );

            $options = Set::insert( $options, 'typevoie', $typevoie );
// debug($options);
            $dept = $this->Departement->find('list', array( 'fields' => array( 'numdep', 'name' ) ) );
            $this->set( compact( 'options', 'dept' ) );

            $this->set( 'rsaSocle', $this->Option->natpf() );
            return $return;
        }


        /**
        *
        */

        public function index( $personne_id = null ) {
            $nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

            /**
            *   Précondition: La personne est-elle bien en Rsa Socle ?
            */
            $alerteRsaSocle = $this->Cui->_prepare( $personne_id );
            $this->set( 'alerteRsaSocle', $alerteRsaSocle );

            $cuis = $this->Cui->find(
                'all',
                array(
                    'conditions' => array(
                        'Cui.personne_id' => $personne_id
                    ),
                    'recursive' => -1
                )
            );

            $this->set( 'personne_id', $personne_id );
            $this->set( compact( 'cuis' ) );
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
                    $id = $this->Cui->field( 'personne_id', array( 'id' => $id ) );
                }
                $this->redirect( array( 'action' => 'index', $id ) );
            }

            $valueAdressebis = null;
            if( $this->action == 'add' ) {
                $cui_id = null;
                $personne_id = $id;
                $nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
                $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
                $valueAdressebis = 'N';

            }
            else if( $this->action == 'edit' ) {
                $cui_id = $id;
                $cui = $this->Cui->findById( $cui_id, null, null, -1 );
                $this->assert( !empty( $cui ), 'invalidParameter' );
                $personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
                $valueAdressebis = Set::classicExtract( $cui, 'Cui.isadresse2' );
            }

            /// Peut-on prendre le jeton ?
            $this->Cui->begin();
            $dossier_id = $this->Cui->Personne->dossierId( $personne_id );
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Cui->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
            $this->set( 'dossier_id', $dossier_id );
            $this->set( 'valueAdressebis', $valueAdressebis );

            ///On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
            $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 0 );
            $user_id = Set::classicExtract( $user, 'User.id' );
            $personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id, $user_id );
            $this->set( 'personne', $personne );


            if( !empty( $this->data ) ){

                $valid = $this->Cui->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) );

                if( $valid  ) {
                    $saved = $this->Cui->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

                    if( $saved ){
                        $this->Jetons->release( $dossier_id );
                        $this->Cui->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Cui->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else {
                if( $this-> action == 'edit' ){
                    $this->data = $cui;
                }
            }

            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        */

        public function delete( $id ) {
            $this->Default->delete( $id );
        }

        /**
        *
        */

        public function view( $id ) {
            $this->Default->view( $id );
        }

    }
?>