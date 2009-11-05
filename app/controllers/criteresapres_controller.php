<?php
    App::import('Sanitize');

    class CriteresapresController extends AppController
    {
        var $name = 'Criteresapres';
        var $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Critereapre', 'Apre', 'Option' );


        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        function beforeFilter() {
            $return = parent::beforeFilter();
/*            $this->set( 'decision_ci', $this->Option->decision_ci() );*/
            return $return;
        }


        function index() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Critereapre->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $apres = $this->paginate( 'Apre' );

                $this->Dossier->commit();

                $this->set( 'apres', $apres );
            }
            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );

        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Critereapre->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $contrats = $this->Apre->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'contrats' ) );
        }
    }
?>