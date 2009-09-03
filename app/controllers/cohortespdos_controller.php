<?php 
    App::import('Sanitize');
    class CohortespdosController extends AppController {

        var $name = 'Cohortespdos';
        var $uses = array( 'Cohortepdo', 'Option', 'Derogation' );

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

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'avisdero', $this->Option->avisdero() );
            $this->set( 'typdero', $this->Option->typdero() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motideccg', $this->Option->motideccg() );
        }

        function index() {
            $this->Cohortepdo->create( $this->data );

            if( !empty( $this->data ) && $this->Cohortepdo->validates() ) {
                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Cohortepdo->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $cohortepdo = $this->paginate( 'Derogation' );

                $this->Dossier->commit();

                $this->set( 'cohortepdo', $cohortepdo );
            }
        }
    }
?>