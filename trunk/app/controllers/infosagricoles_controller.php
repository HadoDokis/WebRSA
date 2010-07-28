<?php
    class InfosagricolesController extends AppController
    {

        var $name = 'Infosagricoles';
        var $uses = array( 'Infoagricole',  'Option' , 'Personne', 'Aideagricole');
        
		var $commeDroit = array(
			'view' => 'Infosagricoles:index'
		);

        function beforeFilter() {
            parent::beforeFilter();
                $this->set( 'regfisagri', $this->Option->regfisagri() );

        }


        function index( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'error404' );


            $infoagricole = $this->Infoagricole->find(
                'first',
                array(
                    'conditions' => array(
                        'Infoagricole.personne_id' => $personne_id
                    ),
                    'recursive' => 1
                )
            ) ;


            // Assignations à la vue
            $this->set( 'personne_id', $personne_id );
            $this->set( 'infoagricole', $infoagricole );
        }


        function view( $infoagricole_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $infoagricole_id ), 'error404' );

            $infoagricole = $this->Infoagricole->find(
                'first',
                array(
                    'conditions' => array(
                        'Infoagricole.id' => $infoagricole_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $infoagricole ), 'error404' );

            // Assignations à la vue
            $this->set( 'personne_id', $infoagricole['Infoagricole']['personne_id'] );
            $this->set( 'infoagricole', $infoagricole );

        }
}
?>
