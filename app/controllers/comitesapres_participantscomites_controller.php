<?php
    class ComitesapresParticipantscomitesController extends AppController
    {

        var $name = 'ComitesapresParticipantscomites';
        var $uses = array( 'ComiteapreParticipantcomite', 'Participantcomite', 'Comiteapre' );
        var $helpers = array( 'Xform' );

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'participantcomite', $this->Participantcomite->find( 'list' ) );
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

        function _add_edit( $id = null ){
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
                if( $this->Comiteapre->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'comitesapres', 'action' => 'view', $comiteapre_id ) );
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
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>