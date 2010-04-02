<?php
    class DetailsdroitsrsaController extends AppController
    {

        var $name = 'Detailsdroitsrsa';
        var $uses = array( 'Detaildroitrsa',  'Option' , 'Dossier', 'Detailcalculdroitrsa');


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'topsansdomfixe', $this->Option->topsansdomfixe() );
            $this->set( 'oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'topfoydrodevorsa', $this->Option->topfoydrodevorsa() );
            $this->set( 'natpf', $this->Option->natpf() );
            $this->set( 'sousnatpf', $this->Option->sousnatpf() );	    
        }


        function index( $dossier_rsa_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'error404' );


            $detaildroitrsa = $this->Detaildroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Detaildroitrsa.dossier_rsa_id' => $dossier_rsa_id
                    ),
                    'recursive' => 1
                )
            ) ;


            // Assignations à la vue
            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'detaildroitrsa', $detaildroitrsa );
        }


        function view( $detaildroitrsa_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $detaildroitrsa_id ), 'error404' );

            $detaildroitrsa = $this->Detaildroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Detaildroitrsa.id' => $detaildroitrsa_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $detaildroitrsa ), 'error404' );

            // Assignations à la vue
            $this->set( 'dossier_id', $detaildroitrsa['Detaildroitrsa']['dossier_rsa_id'] );
            $this->set( 'detaildroitrsa', $detaildroitrsa );

        }
}
?>
