<?php
    class TotalisationsacomptesController extends AppController
    {

        var $name = 'Totalisationsacomptes';
        var $uses = array( 'Totalisationacompte', 'Identificationflux', 'Option', 'Infofinanciere' );
        var $helpers = array( 'Locale', 'Csv' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            // Type_totalisation
            $this->set( 'type_totalisation', $this->Option->type_totalisation() );
//             $this->set( 'totalloccompta', $this->Option->natpfcre( 'totalloccompta' ) );
//             $this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
//             $this->set( 'localrsa', $this->Option->natpfcre( 'localrsa' ) );
            $this->set( 'natpfcre', $this->Option->natpfcre(  ) );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index() {
            if( !empty( $this->data ) ) {
                $params = $this->Totalisationacompte->search( $this->data );
                $totsacoms = $this->Totalisationacompte->find( 'all', $params );
                $this->set('totsacoms', $totsacoms );
            }
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function exportcsv() {
            $params = $this->Totalisationacompte->search( array_multisize( $this->params['named'] ) );
            $totsacoms = $this->Totalisationacompte->find( 'all', $params );

            $identsflux = $this->Identificationflux->find( 'all' );
            $this->set( 'identsflux', $identsflux );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'totsacoms' ) );
        }
    }
?>