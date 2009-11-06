<?php
    App::import('Sanitize');

    class CriteresapresController extends AppController
    {
        var $name = 'Criteresapres';
        var $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Critereapre', 'Apre', 'Option' );

        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        function beforeFilter() {
            $return = parent::beforeFilter();
            $options = $this->Apre->allEnumLists();
            $this->set( 'options', $options );
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
            $apres = $this->Apre->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'apres' ) );
        }
    }
?>