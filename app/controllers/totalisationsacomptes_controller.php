<?php
    class TotalisationsacomptesController extends AppController
    {

        var $name = 'Totalisationsacomptes';
        var $uses = array( 'Totalisationacompte', 'Identificationflux', 'Option', 'Infofinanciere' );


        function beforeFilter() {
            parent::beforeFilter();
            // Type_totalisation
            $this->set( 'type_totalisation', $this->Option->type_totalisation() );
        }


        function index(/* $identificationflux_id = null*/ ){
            // Vérification du format de la variable
//             $this->assert( valid_int( $identificationflux_id ), 'error404' );

//             $totsacoms = $this->Totalisationacompte->find(
//                 'all',
//                 array(
//                     'conditions' => array(
//                         'Totalisationacompte.identificationflux_id' => $identificationflux_id
//                     )
//                 )
//             ) ;

/*            $this->set( 'identificationflux_id', $identificationflux_id);*/

//             $this->set('personne_id', $identificationflux_id );

            if( !empty( $this->data ) ) {
//                 $totsacoms = $this->Totalisationacompte->find( 'all' );
//                 $ident = $this->Infofinanciere->find( 'first' );

                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Totalisationacompte->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $totsacoms = $this->paginate( 'Totalisationacompte' );

                $this->Dossier->commit();
//                 $this->set('ident', $ident );
                $this->set('totsacoms', $totsacoms );

//                 debug( $totsacoms );
                $this->data['Search'] = $this->data;
            }
        }

    }