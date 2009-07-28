<?php
    class TotalisationsacomptesController extends AppController
    {

        var $name = 'Totalisationsacomptes';
        var $uses = array( 'Totalisationacompte', 'Identificationflux', 'Option' );


        function beforeFilter() {
            parent::beforeFilter();
            // Type_totalisation
            $this->set( 'type_totalisation', $this->Option->type_totalisation() );
        }


        function index( $identificationflux_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $identificationflux_id ), 'error404' );

            $totsacoms = $this->Totalisationacompte->find(
                'all',
                array(
                    'conditions' => array(
                        'Totalisationacompte.identificationflux_id' => $identificationflux_id
                    )
                )
            ) ;

            $this->set( 'identificationflux_id', $identificationflux_id);
            $this->set('totsacoms', $totsacoms );
            $this->set('personne_id', $identificationflux_id );
        }

    }