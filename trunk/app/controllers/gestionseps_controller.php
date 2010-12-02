<?php
    class GestionsepsController extends AppController
    {
        var $name = 'Gestionseps';
        var $uses = array( 'Eps' );


        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

            $compteurs = array(
                'Fonctionmembreep' => ClassRegistry::init( 'Fonctionmembreep' )->find( 'count' )
            );
            $this->set( compact( 'compteurs' ) );

        }

    }

?>