<?php
    class TotalisationsacomptesController extends AppController
    {

        var $name = 'Totalisationsacomptes';
        var $uses = array( 'Totalisationacompte', 'Identificationflux', 'Option', 'Infofinanciere' );
        var $helpers = array( 'Locale' );

        function beforeFilter() {
            parent::beforeFilter();
            // Type_totalisation
            $this->set( 'type_totalisation', $this->Option->type_totalisation() );
//             $this->set( 'totalloccompta', $this->Option->natpfcre( 'totalloccompta' ) );
//             $this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
//             $this->set( 'localrsa', $this->Option->natpfcre( 'localrsa' ) );
            $this->set( 'natpfcre', $this->Option->natpfcre(  ) );
        }


        function index(){
            if( !empty( $this->data ) ) {
                $params = $this->Totalisationacompte->search( $this->data );
                $totsacoms = $this->Totalisationacompte->find( 'all', $params );

                $this->set('totsacoms', $totsacoms );
            }
        }
    }
?>