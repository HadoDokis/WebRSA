<?php
    class TypesnotifsController extends AppController
    {

        var $name = 'Typesnotifs';
        var $uses = array( 'Typenotif', 'Propopdo' );

        function index() {

            $typesnotifs = $this->Typenotif->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('typesnotifs', $typesnotifs);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Typenotif->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesnotifs', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typenotif_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typenotif_id ), 'invalidParameter' );

            if( !empty( $this->data ) ) {
                if( $this->Typenotif->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesnotifs', 'action' => 'index' ) );
                }
            }
            else {
                $typenotif = $this->Typenotif->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Typenotif.id' => $typenotif_id,
                        )
                    )
                );
                $this->data = $typenotif;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $typenotif_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $typenotif_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $typenotif = $this->Typenotif->find(
                'first',
                array( 'conditions' => array( 'Typenotif.id' => $typenotif_id )
                )
            );

            // Mauvais paramètre
            if( empty( $typenotif_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Typenotif->delete( array( 'Typenotif.id' => $typenotif_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'typesnotifs', 'action' => 'index' ) );
            }
        }
    }

?>