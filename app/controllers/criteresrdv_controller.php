<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '128M' );
    App::import('Sanitize');

    class CriteresrdvController extends AppController
    {
        var $name = 'Criteresrdv';
        var $uses = array(  'Dossier', 'Foyer', 'Adresse', 'Personne', 'Rendezvous', 'Critererdv', 'Structurereferente', 'Option' );
        var $aucunDroit = array( 'constReq' );

        var $helpers = array( 'Csv' );
        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */


//         var $paginate = array(
//             // FIXME
//             'limit' => 20,
// //             'order' => array(
// //                 'Criteresci.locaadr' => 'asc'
// //             )
//         );

        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }


        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'statutrdv', $this->Option->statutrdv() );
            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );
        }


        function index() {
            if( !empty( $this->data ) ) {

                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
                $this->paginate['limit'] = 10;
                $rdvs = $this->paginate( 'Rendezvous' );

                $this->Dossier->commit();
                $this->set( 'rdvs', $rdvs );
                $this->data['Search'] = $this->data;
            }
        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
            $querydata = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $rdvs = $this->Rendezvous->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'rdvs' ) );
        }
    }
?>
