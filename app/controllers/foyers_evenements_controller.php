<?php
    class FoyersEvenementsController extends AppController
    {

        var $name = 'FoyersEvenements';
        var $uses = array( 'FoyerEvenement', 'Option', 'Foyer', 'Evenement' );
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

            $foyers_evenements = $this->FoyerEvenement->find(
                'all',
                array(
                    'conditions' => array(
                        'FoyerEvenement.foyer_id' => $foyer_id
                    )
                )
            );
            $this->set( 'foyers_evenements', $foyers_evenements );
// debug($foyers_evenements);
            $this->set( 'foyer_id', $foyer_id );
        }

    }
?>