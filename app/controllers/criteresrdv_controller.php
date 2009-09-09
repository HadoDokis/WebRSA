<?php

    App::import('Sanitize');

    class CriteresrdvController extends AppController
    {
        var $name = 'Criteresrdv';
        var $uses = array(  'Dossier', 'Foyer', 'Adresse', 'Personne', 'Rendezvous', 'Structurereferente', 'Option' );
        var $aucunDroit = array( 'constReq' );

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

                $this->paginate = $this->Rendezvous->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
                $this->paginate['limit'] = 10;
                $rdvs = $this->paginate( 'Rendezvous' );

                $this->Dossier->commit();
// debug( $rdvs );
                $this->set( 'rdvs', $rdvs );
                $this->data['Search'] = $this->data;
            }
        }

    }
?>
