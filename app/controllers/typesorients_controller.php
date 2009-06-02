<?php
    class TypesorientsController extends AppController
    {

        var $name = 'Typesorients';
        var $uses = array( 'Typeorient', 'Structurereferente');

        function index() {

            $typesorients = $this->Typeorient->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('typesorients', $typesorients);
        }

        function add() {
            $this->set( 'options', $this->Typeorient->listOptions() );

            $typesorients = $this->Typeorient->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('typesorients', $typesorients);

            $parentid = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient',
                    ),
                    'conditions' => array( 'Typeorient.parentid' => null )
                )
            );
            $this->set( 'parentid', $parentid );


            if( !empty( $this->data ) ) {
                if( $this->Typeorient->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
                }
            }


	    $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typeorient_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typeorient_id ), 'error404' );

            $typesorients = $this->Typeorient->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('typesorients', $typesorients);

            $parentid = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient',
                    ),
                    'conditions' => array( 'Typeorient.parentid' => null )
                )
            );
            $this->set( 'parentid', $parentid );

            $notif = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.modele_notif'
                    )
                )
            );

            $this->set( 'notif', $notif );

            if( !empty( $this->data ) ) {
                if( $this->Typeorient->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
                }
            }
            else {
                $typeorient = $this->Typeorient->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Typeorient.id' => $typeorient_id,
                        )
                    )
                );
                $this->data = $typeorient;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

    }

?>