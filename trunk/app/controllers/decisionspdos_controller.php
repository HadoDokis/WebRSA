<?php
    class DecisionspdosController extends AppController
    {

        var $name = 'Decisionspdos';
        var $uses = array( 'Decisionpdo', 'Propopdo' );

        function index() {

            $decisionspdos = $this->Decisionpdo->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('decisionspdos', $decisionspdos);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Decisionpdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'decisionspdos', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $decisionpdo_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $decisionpdo_id ), 'invalidParameter' );

            if( !empty( $this->data ) ) {
                if( $this->Decisionpdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'decisionspdos', 'action' => 'index' ) );
                }
            }
            else {
                $decisionpdo = $this->Decisionpdo->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Decisionpdo.id' => $decisionpdo_id,
                        )
                    )
                );
                $this->data = $decisionpdo;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $decisionpdo_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $decisionpdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $decisionpdo = $this->Decisionpdo->find(
                'first',
                array( 'conditions' => array( 'Decisionpdo.id' => $decisionpdo_id )
                )
            );

            // Mauvais paramètre
            if( empty( $decisionpdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Decisionpdo->delete( array( 'Decisionpdo.id' => $decisionpdo_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'decisionspdos', 'action' => 'index' ) );
            }
        }
    }

?>