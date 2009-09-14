<?php 
    App::import('Sanitize');
    class CohortespdosController extends AppController {

        var $name = 'Cohortespdos';
        var $uses = array( 'Cohortepdo', 'Option', 'Dossier', 'Situationdossierrsa', 'Propopdo' );
        var $helpers = array( 'Csv', 'Paginator' );

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );

        /**
        */
//         function __construct() {
//             $this->components = Set::merge( $this->components, array( 'Jetons', 'Prg' => array( 'actions' => array( 'index' ) ) ) );
//             parent::__construct();
//         }
        function __construct() {
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'decisionpdo', $this->Option->decisionpdo() );
            $this->set( 'typepdo', $this->Option->typepdo() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motideccg', $this->Option->motideccg() );
        }

        //*********************************************************************

        function avisdemande() {
            $this->_index( 'Decisionpdo::nonvalide' );
        }

        //---------------------------------------------------------------------

        function valide() {
            $this->_index( 'Decisionpdo::valide' );
        }
        //---------------------------------------------------------------------

        //*********************************************************************

        function _index( $statutValidationAvis = null ) {
            $this->assert( !empty( $statutValidationAvis ), 'invalidParameter' );

//             $this->Cohortepdo->create( $this->data );

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );


            if( !empty( $this->data ) ) {
                if( !empty( $this->data['Propopdo'] ) ) {
                    $valid = $this->Propopdo->saveAll( $this->data['Propopdo'], array( 'validate' => 'only', 'atomic' => false ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Propopdo->saveAll( $this->data['Propopdo'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            //FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Propopdo.{n}.dossier_id' ) ) as $dossier_id ) {
                                $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                            }
                            $this->Dossier->commit();
                            $this->data['Propopdo'] = array();

                        }
                        else {
                            $this->Dossier->rollback();
                        }
                    }
                }
            }

            if( ( $statutValidationAvis == 'Decisionpdo::nonvalide' ) || ( ( $statutValidationAvis == 'Decisionpdo::valide' ) && !empty( $this->data ) ) ) {
                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Cohortepdo->search( $statutValidationAvis, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $cohortepdo = $this->paginate( 'Dossier' );

                $this->Dossier->commit();
                $this->set( 'cohortepdo', $cohortepdo );
            }


            switch( $statutValidationAvis ) {
                case 'Decisionpdo::nonvalide':
                    $this->set( 'pageTitle', 'Nouvelles demandes PDOs' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Decisionpdo::valide':
                    $this->set( 'pageTitle', 'PDOs validés' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
//             $statutValidationAvis = $this->Propopdo->find( 'list', array( 'fields' => array( 'decisionpdo' ) ) );

            $_limit = 10;
            $params = $this->Cohortepdo->search( 'Decisionpdo::valide', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );

            unset( $params['limit'] );
            $pdos = $this->Propopdo->find( 'all', $params );


            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'pdos' ) );
        }
    }
?>