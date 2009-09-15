<?php
    App::import('Sanitize');

    class CriteresController extends AppController
    {
        var $name = 'Criteres';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Orientstruct' );
        //var $aucunDroit = array('index', 'menu', 'constReq');
        var $aucunDroit = array( 'constReq' );
        var $helpers = array( 'Csv' );

        /**
        *
        *
        *
        */

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $typeservice = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'typeservice', $typeservice );

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );


            $this->set( 'typeorient', $this->Typeorient->listOptions() );
            $this->set( 'statuts', $this->Option->statut_orient() );
            $this->set( 'statuts_contrat', $this->Option->statut_contrat_insertion() );
            $this->set( 'typeservice', $this->Serviceinstructeur->listOptions());

            return $return;
        }

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */
        function index() {
            if( !empty( $this->data ) ) {

                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Orientstruct->search(
                    $mesCodesInsee,
                    $this->Session->read( 'Auth.User.filtre_zone_geo' ),
                    $this->data,
                    $this->Jetons->ids()
                );

                $this->paginate['limit'] = 10;
                $orients = $this->paginate( 'Orientstruct' );

                $this->Dossier->commit();
// debug( $orients );
                $this->set( 'orients', $orients );
                $this->data['Search'] = $this->data;
            }
        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Orientstruct->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

            unset( $querydata['limit'] );
            $orients = $this->Orientstruct->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'orients' ) );
        }
    }
?>
