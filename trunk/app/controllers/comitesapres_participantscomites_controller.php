<?php
    class ComitesapresParticipantscomitesController extends AppController
    {

        var $name = 'ComitesapresParticipantscomites';
        var $uses = array( 'ComiteapreParticipantcomite', 'Apre', 'Participantcomite', 'Comiteapre' );
        var $components = array( 'Jetonsfonctions' );
        var $helpers = array( 'Xform' );



        protected function _setOptions() {
            $this->set( 'participants', $this->Participantcomite->find( 'all' ) );
            $options = $this->ComiteapreParticipantcomite->allEnumLists();
            $this->set( 'options', $options );
        }
/*
        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'participants', $this->Participantcomite->find( 'all' ) );
            $options = $this->ComiteapreParticipantcomite->allEnumLists();
            $this->set( 'options', $options );
            //$this->set( 'options', $options );
        }*/

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
        *   Ajout et Modification des participants à un comité donné
        *** *******************************************************************/

        function _add_edit( $id = null ){

            $this->Comiteapre->begin();

            if( $this->Jetonsfonctions->get( $this->name, $this->action ) ) {

                if( $this->action == 'add' ) {
                    $comiteapre_id = $id;
                    $nbrComites = $this->Comiteapre->find( 'count', array( 'conditions' => array( 'Comiteapre.id' => $comiteapre_id ), 'recursive' => -1 ) );
                    $this->assert( ( $nbrComites == 1 ), 'invalidParameter' );
                }
                else if( $this->action == 'edit' ) {
                    $comiteapre_id = $id;
                    $comiteparticipant = $this->ComiteapreParticipantcomite->find(
                        'all',
                        array(
                            'conditions' => array(
                                'ComiteapreParticipantcomite.comiteapre_id' => $comiteapre_id
                            )
                        )
                    );
                    $this->assert( !empty( $comiteparticipant ), 'invalidParameter' );
                }

                if( !empty( $this->data ) ) {
                    foreach( $this->data['Participantcomite']['Participantcomite'] as $i => $participantcomiteId ) {
                        if( empty( $participantcomiteId ) ) {
                            unset( $this->data['Participantcomite']['Participantcomite'][$i] );
                        }
                    }
                    $this->Jetonsfonctions->release( $this->name, $this->action );
                    $this->Comiteapre->commit();
                    if( $this->Comiteapre->saveAll( $this->data ) ) {
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'comitesapres', 'action' => 'view', $comiteapre_id ) );
                    }
                    else{
                        $this->Comiteapre->rollback();
                    }
                }
                else {
                    if( $this->action == 'edit' ) {
                        $this->data = array(
                            'Comiteapre' => array(
                                'id' => $comiteapre_id,
                            ),
                            'Participantcomite' => array(
                                'Participantcomite' => Set::extract( $comiteparticipant, '/ComiteapreParticipantcomite/participantcomite_id' )
                            )
                        );
                    }
                    else {
                        $this->data['Comiteapre']['id'] = $comiteapre_id;
                    }
                }
                $this->_setOptions();
                $this->Comiteapre->commit();
                $this->render( $this->action, null, 'add_edit' );
            }
        }


        /** ********************************************************************
        *   Recensement de la présence des participants au comité
        *** *******************************************************************/

        function rapport( $comiteapre_id = null ){

            $comiteparticipant = $this->ComiteapreParticipantcomite->find(
                'all',
                array(
                    'conditions' => array(
                        'ComiteapreParticipantcomite.comiteapre_id' => $comiteapre_id
                    )
                )
            );
            $this->assert( !empty( $comiteparticipant ), 'invalidParameter' );
            $this->set( 'participants', $comiteparticipant );

            if( !empty( $this->data ) ) {
                $this->ComiteapreParticipantcomite->begin();
                $success = true;
                foreach( $this->data['ComiteapreParticipantcomite'] as $item ) {
//                     debug( $item );
                    $success = $this->ComiteapreParticipantcomite->create( array( 'ComiteapreParticipantcomite' => $item ) ) && $success;
                    $this->ComiteapreParticipantcomite->save();
                }

                if( $success ) {
                    $this->ComiteapreParticipantcomite->commit();
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'comitesapres', 'action' => 'rapport', $comiteapre_id ) );
                }
                else {
                    $this->ComiteapreParticipantcomite->rollback();
                }
            }
            else {
                $this->data = $comiteparticipant;
            }
            $this->_setOptions();
        }
    }
?>