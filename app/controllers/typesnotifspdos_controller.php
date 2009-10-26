<?php
    class TypesnotifspdosController extends AppController
    {

        var $name = 'Typesnotifspdos';
        var $uses = array( 'Typenotifpdo', 'Propopdo' );

        function index() {

            $typesnotifspdos = $this->Typenotifpdo->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('typesnotifspdos', $typesnotifspdos);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Typenotifpdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesnotifspdos', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typenotifpdo_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typenotifpdo_id ), 'invalidParameter' );

            if( !empty( $this->data ) ) {
                if( $this->Typenotifpdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesnotifspdos', 'action' => 'index' ) );
                }
            }
            else {
                $typenotifpdo = $this->Typenotifpdo->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Typenotifpdo.id' => $typenotifpdo_id,
                        )
                    )
                );
                $this->data = $typenotifpdo;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function deleteparametrage( $typenotifpdo_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $typenotifpdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $typenotifpdo = $this->Typenotifpdo->find(
                'first',
                array( 'conditions' => array( 'Typenotifpdo.id' => $typenotifpdo_id )
                )
            );

            // Mauvais paramètre
            if( empty( $typenotifpdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Typenotifpdo->delete( array( 'Typenotifpdo.id' => $typenotifpdo_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'typesnotifspdos', 'action' => 'index' ) );
            }
        }
    }

?>