<?php
    class PdosController extends AppController
    {
        var $name = 'Pdos';
        var $uses = array( 'Dossier',  'Propopdo' );


        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

        }


        function edit( $param = null ) {

        }

    }

?>