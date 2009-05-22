<?php
    class TypesorientsController extends AppController
    {

        var $name = 'Typesorients';
        var $uses = array( 'Typeorient', 'User', 'Adresse', 'Structurereferente', 'Typeorient');

        function index() {

            $zones = $this->Typeorient->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('zones', $zones);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Typeorient->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $zone_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $zone_id ), 'error404' );

            if( !empty( $this->data ) ) {
                if( $this->Typeorient->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
                }
            }
            else {
                $zone = $this->Typeorient->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Typeorient.id' => $zone_id,
                        )
                    )
                );
                $this->data = $zone;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

    }

?>