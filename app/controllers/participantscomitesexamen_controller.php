<?php

    class ParticipantscomitesexamenController extends AppController
    {
        var $name = 'Participantscomitesexamen';
        var $uses = array( 'Participantcomiteexamen', 'Comiteexamenapre', 'Option' );
        var $helpers = array( 'Xform' );

        function beforeFilter() {
            $this->set( 'qual', $this->Option->qual() );
        }

        function index() {
            $participants = $this->Participantcomiteexamen->find( 'all', array( 'recursive' => -1 ) );
            $this->set('participants', $participants );
        }

        function add() {
            if( !empty( $this->data ) ) {
                if( $this->Participantcomiteexamen->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'participantscomitesexamen', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $participant_id = null ) {
            $this->assert( valid_int( $participant_id ) , 'invalidParameter' );

            if( !empty( $this->data ) ) {
                if( $this->Participantcomiteexamen->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'participantscomitesexamen', 'action' => 'index' ) );
                }
            }
            else {
                $participant = $this->Participantcomiteexamen->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Participantcomiteexamen.id' => $participant_id,
                        )
                    )
                );
                $this->data = $participant;
            }

            $this->render( $this->action, null, 'add_edit' );
        }


        function delete( $participant_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $participant_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $participant = $this->Participantcomiteexamen->find(
                'first',
                array( 'conditions' => array( 'Participantcomiteexamen.id' => $participant_id )
                )
            );

            // Mauvais paramètre
            if( empty( $participant_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Participantcomiteexamen->delete( array( 'Participantcomiteexamen.id' => $participant_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'participantscomitesexamen', 'action' => 'index' ) );
            }
        }
    }
?>