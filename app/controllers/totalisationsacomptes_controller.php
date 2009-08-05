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
        }


        function index(){

            if( !empty( $this->data ) ) {

                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Totalisationacompte->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $totsacoms = $this->paginate( 'Totalisationacompte' );

                $this->Dossier->commit();
                $this->set('totsacoms', $totsacoms );

//                 debug( $totsacoms );
                $this->data['Search'] = $this->data;
            }
        }

    }