<?php
    class AvispcgdroitrsaController extends AppController
    {

        var $name = 'Avispcgdroitrsa';
        var $uses = array( 'Avispcgdroitrsa',  'Option' , 'Dossier', 'Condadmin',  'Reducrsa',);


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'avisdestpairsa', $this->Option->avisdestpairsa() );
            $this->set( 'typeperstie', $this->Option->typeperstie() );
            $this->set( 'aviscondadmrsa', $this->Option->aviscondadmrsa() );
        }


        function index( $dossier_rsa_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'error404' );


            $avispcgdroitrsa = $this->Avispcgdroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Avispcgdroitrsa.dossier_rsa_id' => $dossier_rsa_id
                    ),
                    'recursive' => 1
                )
            ) ;


            // Assignations à la vue
            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'avispcgdroitrsa', $avispcgdroitrsa );
        }


        function view( $avispcgdroitrsa_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $avispcgdroitrsa_id ), 'error404' );

            $avispcgdroitrsa = $this->Avispcgdroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Avispcgdroitrsa.id' => $avispcgdroitrsa_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $avispcgdroitrsa ), 'error404' );

            // Assignations à la vue
            $this->set( 'dossier_id', $avispcgdroitrsa['Avispcgdroitrsa']['dossier_rsa_id'] );
            $this->set( 'avispcgdroitrsa', $avispcgdroitrsa );

        }

}
?>
