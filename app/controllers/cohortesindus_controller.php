<?php
    App::import('Sanitize');

    class CohortesindusController extends AppController
    {
        var $name = 'Cohortesindus';
        var $uses = array( 'Cohorteindu', 'Option',  'Structurereferente', 'Infofinanciere', 'Dossier' );
        var $helpers = array( 'Csv', 'Paginator', 'Locale' );

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );

        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Jetons', 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        function beforeFilter() {
            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );


            $return = parent::beforeFilter();
                $this->set( 'natpfcre', $this->Option->natpfcre( 'autreannulation' ) );
                $this->set( 'typeparte', $this->Option->typeparte() );
                $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
                $this->set( 'type_allocation', $this->Option->type_allocation() );
                $this->set( 'dif', $this->Option->dif() );
            return $return;
        }

        function index() {
                $comparators = array( '<' => '<' ,'>' => '>','<=' => '<=', '>=' => '>=' );

                $cmp = Set::extract( $this->data, 'Cohorteindu.compare' );
                $this->assert( empty( $cmp ) || in_array( $cmp, array_keys( $comparators ) ), 'invalidParameter' );
                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
                $this->Cohorteindu->create( $this->data );
                if( !empty( $this->data ) && $this->Cohorteindu->validates() ) {
                    $this->Dossier->begin(); // Pour les jetons

                    $this->paginate = $this->Cohorteindu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                    $this->paginate['limit'] = 10;
                    $cohorteindu = $this->paginate( 'Dossier' );

                    $this->Dossier->commit();

                    $this->set( 'cohorteindu', $cohorteindu );
                }
                $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee ) );
                $this->set( 'comparators', $comparators );
        }

        function exportcsv(){
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $_limit = 10;
            $params = $this->Cohorteindu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );

            unset( $params['limit'] );
            $indus = $this->Dossier->find( 'all', $params );


            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'indus' ) );
        }
    }
?>