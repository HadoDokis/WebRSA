<?php 
    App::import('Sanitize');
    class CohortespdosController extends AppController {

        var $name = 'Cohortespdos';
        var $uses = array( 'Cohortepdo', 'Option', 'Derogation', 'Avispcgpersonne' );

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

        //*********************************************************************

        function avisdemande() {
            $this->_index( 'D' );
        }

        //---------------------------------------------------------------------

        function valide() {
            $this->_index( 'O' );
        }
        //---------------------------------------------------------------------

        //*********************************************************************

        function _index( $statutAvis = null ) {
            $this->assert( !empty( $statutAvis ), 'invalidParameter' );

//             $this->Cohortepdo->create( $this->data );

            if( !empty( $this->data ) ) {
                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

// debug( $this->data );
                if( !empty( $this->data['Derogation'] ) ) {
                    $valid = $this->Derogation->saveAll( $this->data['Derogation'], array( 'validate' => 'only', 'atomic' => false ) );
//                         $valid = ( count( $this->Dossier->Foyer->Personne->Avispcgpersonne->Derogation->validationErrors ) == 0 );

                        if( $valid ) {
                            $this->Dossier->begin();
//                             foreach( $this->data['Derogation'] as $key => $value ) {
//                                     $this->data['Derogation']['avisdero'] = $statutAvis;
//                             }
//                             $this->data['Derogation']['avispcgpersonne_id'] = $avispcgpersonne_id;
                            $saved = $this->Derogation->saveAll( $this->data['Derogation'], array( 'validate' => 'first', 'atomic' => false ) );

                            if( $saved ) {
                                //FIXME ?
                                foreach( array_unique( Set::extract( $this->data, 'Derogation.{n}.dossier_id' ) ) as $dossier_id ) {
                                    $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                                }
                                $this->Dossier->commit();
                                $this->data['Derogation'] = array();
                            }
                            else {
                                $this->Dossier->rollback();
                            }
                        }
                }

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Cohortepdo->search( $statutAvis, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $cohortepdo = $this->paginate( 'Derogation' );

                $this->Dossier->commit();

                $this->set( 'cohortepdo', $cohortepdo );
            }


            switch( $statutAvis ) {
                case 'D':
                    $this->set( 'pageTitle', 'Nouvelles demandes PDOs' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'O':
                    $this->set( 'pageTitle', 'PDOs validés' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }
        }
    }
?>