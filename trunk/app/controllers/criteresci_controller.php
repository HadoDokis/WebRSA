<?php

    App::import('Sanitize');

    class CriteresciController extends AppController
    {
        var $name = 'Criteresci';
        var $uses = array(  'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typocontrat', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Cohorteci' );
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

//             $typeservice = $this->Serviceinstructeur->find(
//                 'list',
//                 array(
//                     'fields' => array(
//                         'Serviceinstructeur.id',
//                         'Serviceinstructeur.lib_service'
//                     ),
//                 )
//             );
//             $this->set( 'typeservice', $typeservice );

            $personne_suivi = $this->Contratinsertion->find(
                'list',
                array(
                    'fields' => array(
                        'Contratinsertion.pers_charg_suivi',
                        'Contratinsertion.pers_charg_suivi'
                    ),
                    'order' => 'Contratinsertion.pers_charg_suivi ASC',
                    'group' => 'Contratinsertion.pers_charg_suivi',
                )
            );
            $this->set( 'personne_suivi', $personne_suivi );

            $this->set( 'decision_ci', $this->Option->decision_ci() );
            return $return;
        }


        function index() {
            $params = $this->data;
            if( !empty( $params ) ) {

                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Cohorteci->search( null, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $contrats = $this->paginate( 'Contratinsertion' );

                $this->Dossier->commit();

                $this->set( 'contrats', $contrats );
                $this->data['Search'] = $params;
            }
        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Cohorteci->search( null, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $contrats = $this->Contratinsertion->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'contrats' ) );
        }
    }
?>
