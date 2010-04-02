<?php
    class EvenementsController extends AppController
    {

        var $name = 'Evenements';
        var $uses = array( /*'FoyerEvenement',*/ 'Option', 'Foyer', 'Evenement' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );


        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'fg', $this->Option->fg() );
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $foyer_id = null ){
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            $evenements = $this->Evenement->find(
                'all',
                array(
                    'conditions' => array(
                        'Evenement.foyer_id' => $foyer_id
                    )
                )
            );
            $this->set( 'evenements', $evenements );

            $this->set( 'foyer_id', $foyer_id );
        }

    }
?>