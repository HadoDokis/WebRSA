<?php
    class GrossessesController extends AppController
    {

        var $name = 'Grossesses';
        var $uses = array( 'Grossesse',  'Option' , 'Personne' );


        function beforeFilter() {
            parent::beforeFilter();
     	      $this->set( 'topressevaeti', $this->Option->topressevaeti() );
              $this->set( 'natfingro', $this->Option->natfingro() );
        }


        function index( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'error404' );


            $grossesse = $this->Grossesse->find(
                'first',
                array(
                    'conditions' => array(
                        'Grossesse.personne_id' => $personne_id
                    ),
                    'recursive' => 1
                )
            ) ;


            // Assignations à la vue
            $this->set( 'personne_id', $personne_id );
            $this->set( 'grossesse', $grossesse );
        }


        function view( $grossesse_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $grossesse_id ), 'error404' );

            $grossesse = $this->Grossesse->find(
                'first',
                array(
                    'conditions' => array(
                        'Grossesse.id' => $grossesse_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $grossesse ), 'error404' );

            // Assignations à la vue
            $this->set( 'personne_id', $grossesse['Grossesse']['personne_id'] );
            $this->set( 'grossesse', $grossesse );

        }
}
?>
