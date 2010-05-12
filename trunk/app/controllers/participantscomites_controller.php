<?php

    class ParticipantscomitesController extends AppController
    {
        var $name = 'Participantscomites';
        var $uses = array( 'Participantcomite', 'Comiteapre', 'Option' );
        var $helpers = array( 'Xform' );

        function beforeFilter() {
            $this->set( 'qual', $this->Option->qual() );
        }

        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams' ) );
            }

            $participants = $this->Participantcomite->find( 'all', array( 'recursive' => -1 ) );
            $this->set('participants', $participants );
        }

        function add() {
            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

            if( !empty( $this->data ) ) {
                if( $this->Participantcomite->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'participantscomites', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $participant_id = null ) {
            $this->assert( valid_int( $participant_id ) , 'invalidParameter' );
            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

            if( !empty( $this->data ) ) {
                if( $this->Participantcomite->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'participantscomites', 'action' => 'index' ) );
                }
            }
            else {
                $participant = $this->Participantcomite->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Participantcomite.id' => $participant_id,
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
            $participant = $this->Participantcomite->find(
                'first',
                array( 'conditions' => array( 'Participantcomite.id' => $participant_id )
                )
            );

            // Mauvais paramètre
            if( empty( $participant_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Participantcomite->delete( array( 'Participantcomite.id' => $participant_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'participantscomites', 'action' => 'index' ) );
            }
        }

    }
?>