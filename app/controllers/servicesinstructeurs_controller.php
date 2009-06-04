<?php
    class ServicesinstructeursController extends AppController
    {

        var $name = 'Servicesinstructeurs';
        var $uses = array( 'Serviceinstructeur', 'Option' );

         function beforeFilter() {
            parent::beforeFilter();
                $this->set( 'typeserins', $this->Option->typeserins() );
        }

        function index() {

            $servicesinstructeurs = $this->Serviceinstructeur->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('servicesinstructeurs', $servicesinstructeurs);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Serviceinstructeur->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $serviceinstructeur_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $serviceinstructeur_id ), 'error404' );


            if( !empty( $this->data ) ) {
                if( $this->Serviceinstructeur->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
                }
            }
            else {
                $serviceinstructeur = $this->Serviceinstructeur->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Serviceinstructeur.id' => $serviceinstructeur_id,
                        )
                    )
                );
                $this->data = $serviceinstructeur;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

    }

?>