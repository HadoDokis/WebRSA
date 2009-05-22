<?php
    class ParametragesController extends AppController
    {
        var $name = 'Parametrages';
        var $uses = array( 'Dossier', 'Structurereferente', 'Zonegeographique' );

        function index() {

        }

        function view( $param = null ) {


            $zone = $this->Zonegeographique->find(
                'first',
                array(
                    'conditions' => array(
                    )
                )
            );

            $this->set('zone', $zone);
        }

        function edit( $param = null ) {

        }

    }

?>