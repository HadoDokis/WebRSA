<?php
    class BilanparcoursController extends AppController
    {

        var $name = 'Bilanparcours';
        var $uses = array( 'Bilanparcours', 'Option', 'Personne', 'PersonneReferent', 'Structurereferente', 'Referent' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );


        /** ********************************************************************
        *
        *** *******************************************************************/
        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();
            $options = $this->Bilanparcours->allEnumLists();
            $typevoie = $this->Option->typevoie();
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'nationalite', $this->Option->nationalite() );

            $options = Set::insert( $options, 'typevoie', $typevoie );

            $options[$this->modelClass]['structurereferente_id'] = $this->{$this->modelClass}->Structurereferente->listOptions();
            $options[$this->modelClass]['referent_id'] = $this->{$this->modelClass}->Referent->listOptions();
            $options[$this->modelClass]['nvsansep_referent_id'] = $this->{$this->modelClass}->Referent->find( 'list' );
            $options[$this->modelClass]['nvparcours_referent_id'] = $this->{$this->modelClass}->Referent->find( 'list' );

            $this->set( compact( 'options' ) );

            $this->set( 'rsaSocle', $this->Option->natpf() );
            return $return;
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
// $this->set( Inflector::tableize( 'Bilanparcours' ) );
            $nbrPersonnes = $this->Bilanparcours->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

//             Inflector::tableize( $this->modelClass )



            /**
            *   Recherche du nombre de référent lié au parcours de la personne
            *   Si aucun alors message d'erreur signalant l'absence de référent (cg66)
            **/
            $persreferent = $this->PersonneReferent->find( 'count', array('conditions' => array( 'PersonneReferent.personne_id' => $personne_id ), 'recursive' => -1 ) );
            $this->set( compact( 'persreferent' ) );

            $bilanparcours = $this->Bilanparcours->find(
                'all',
                array(
                    'conditions' => array(
                        'Bilanparcours.personne_id' => $personne_id
                    )
                )
            );

            ///S'il n'y a pas d'orientation, IMPOSSIBLE de créer un contrat
/*            $orientstruct = $this->Bilanparcours->Structurereferente->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id,
                        'Orientstruct.typeorient_id IS NOT NULL',
                        'Orientstruct.statut_orient' => 'Orienté'
                    ),
                    'order' => 'Orientstruct.date_valid DESC'
                )
            );

            if( !empty( $orientstruct ) ){
                ///S'il n'y a pas de référents, IMPOSSIBLE de créer un contrat
                $referents = $this->Referent->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Referent.structurereferente_id' => Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' )
                        ),
                        'recursive' => -1
                    )
                );

                $this->set( 'referents', $referents );
                $sr = $this->Bilanparcours->Structurereferente->listeParType( array( 'contratengagement' => true ) );
                $struct = Set::enum( Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' ), $sr );
                $this->set( 'struct', $struct );
            }
            else if( empty( $orientstruct ) ) {
                $sr = $this->Bilanparcours->Structurereferente->listeParType( array( 'contratengagement' => true ) );
            }
*/
            $this->set( compact(/* 'orientstruct',*/ 'bilanparcours' ) );
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
                if( $this->action == 'edit' ) {
                    $id = $this->Bilanparcours->field( 'personne_id', array( 'id' => $id ) );
                }
                $this->redirect( array( 'action' => 'index', $id ) );
            }
            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $bilanparcours_id = $id;
                $bilanparcours = $this->Bilanparcours->findById( $bilanparcours_id, null, null, -1 );
                $this->assert( !empty( $bilanparcours ), 'invalidParameter' );

                $personne_id = $bilanparcours['Bilanparcours']['personne_id'];
//                 $dossier_rsa_id = $this->Bilanparcours->dossierId( $bilanparcours_id );
            }

            $this->Bilanparcours->begin();

            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Bilanparcours->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            //On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
            $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 0 );
            $user_id = Set::classicExtract( $user, 'User.id' );
            $personne = $this->Bilanparcours->Personne->detailsCi( $personne_id, $user_id );
            $this->set( 'personne', $personne );

            if( !empty( $this->data ) ){

                if( $this->Bilanparcours->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Bilanparcours->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Bilanparcours->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'bilanparcours','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {

                    $bilanparcours['Bilanparcours']['referent_id'] = $bilanparcours['Bilanparcours']['structurereferente_id'].'_'.$bilanparcours['Bilanparcours']['referent_id'];
                    $this->data = $bilanparcours;
//                     debug($bilanparcours);
                }
            }
            $this->Bilanparcours->commit();


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