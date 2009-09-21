<?php

    class ActionsController extends Appcontroller
    {

        var $name = 'Actions';
        var $uses = array( 'Actioninsertion', 'Aidedirecte', 'Prestform', 'Option', 'Refpresta', 'Action', 'Typeaction' );


         function beforeFilter() {
            parent::beforeFilter();
            $libtypaction = $this->Typeaction->find( 'list', array( 'fields' => array( 'libelle' ) ) );
            $this->set( 'libtypaction', $libtypaction );
        }

        function index() {
            $actions = $this->Action->find(
                'all',
                array(
                    'recursive' => 1
                )
            );
            $this->set( 'actions', $actions );
        }

        function add() {
            if( !empty( $this->data ) ) {
                $this->Action->begin();
                if( $this->Action->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
                    $this->Action->commit();
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'actions', 'action' => 'index' ) );
                }
                else {
                    $this->Action->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $action_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $action_id ), 'invalidParameter' );

            $action = $this->Action->find(
                'first',
                array(
                    'conditions' => array(
                        'Action.id' => $action_id
                    ),
                    'recursive' => 2
                )
            );

            // Si action n'existe pas -> 404
            if( empty( $action ) ) {
                $this->cakeError( 'error404' );
            }

            if( !empty( $this->data ) ) {
                if( $this->Action->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'actions', 'action' => 'index', $action['Action']['id']) );
                }
            }
            else {
                $this->data = $action;
            }
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>