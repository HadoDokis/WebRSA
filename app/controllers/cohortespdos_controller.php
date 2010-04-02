<?php
    App::import('Sanitize');
    class CohortespdosController extends AppController {

        var $name = 'Cohortespdos';
        var $uses = array( 'Canton', 'Cohortepdo', 'Option', 'Dossier', 'Situationdossierrsa', 'Propopdo', 'Typenotifpdo', 'Typepdo', 'Decisionpdo' );
        var $helpers = array( 'Csv', 'Paginator' );

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );

        /**
        */

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( /* 'avisdemande',*/ 'valide' ) ) ) );
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        function beforeFilter(){
            parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
            $this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
            $this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
//             $this->set( 'motideccg', $this->Option->motideccg() );
            $this->set( 'motifpdo', $this->Option->motifpdo() );
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

        function enattente() {
            $this->_index( 'Decisionpdo::enattente' );
        }


        //*********************************************************************

        function _index( $statutValidationAvis = null ) {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}
            $this->assert( !empty( $statutValidationAvis ), 'invalidParameter' );

//             $this->Cohortepdo->create( $this->data );

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $this->Dossier->begin();
            if( !empty( $this->data ) ) {
                if( !empty( $this->data['Propopdo'] ) ) {
                    $valid = $this->Propopdo->saveAll( $this->data['Propopdo'], array( 'validate' => 'only', 'atomic' => false ) );
//                     $data = Set::extract( $this->data, '/Propopdo' );
//                     if( $this->Propopdo->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
//                         $saved = $this->Propopdo->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );
//                         if( array_search( 0, $saved ) == false ) {
//                             //FIXME ?
//                             foreach( array_unique( Set::extract( $this->data, 'Propopdo.{n}.dossier_id' ) ) as $dossier_id ) {
//                                 $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
//                             }
// 
//                             $this->Dossier->commit();
// //                             $this->data = array();
//                         }
//                         else {
//                             $this->Dossier->rollback();
//                         }
//                     }
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Propopdo->saveAll( $this->data['Propopdo'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            // FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Propopdo.{n}.dossier_id' ) ) as $dossier_id ) {
                                $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                            }
                            $this->Dossier->commit();
                            $this->data['Propopdo'] = array(); //FIXME: voir si on peut mieux faire
                        }
                        else {
                            $this->Dossier->rollback();
                        }
                    }
                }

                if( ( $statutValidationAvis == 'Decisionpdo::nonvalide' ) || ( ( $statutValidationAvis == 'Decisionpdo::valide' ) && !empty( $this->data ) ) || ( ( $statutValidationAvis == 'Decisionpdo::enattente' ) && !empty( $this->data ) ) ) {
                    $this->Dossier->begin(); // Pour les jetons

                    $queryData = $this->Cohortepdo->search( $statutValidationAvis, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
    //                 $_limit = 10;
                    $queryData['limit'] = 10;
                    $this->paginate['Dossier'] = $queryData;
                    $cohortepdo = $this->paginate( 'Dossier' );

    //                 $count = count( $this->Cohortepdo->search( $statutValidationAvis, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() ) );
    //                 $this->set( 'count', $count );
    // //                 debug( $count );
    //                 $this->set( 'pages', ceil( $count / $_limit ) );

                    $this->Dossier->commit();
                    $this->set( 'cohortepdo', $cohortepdo );
                }
            }

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

            switch( $statutValidationAvis ) {
                case 'Decisionpdo::nonvalide':
                    $this->set( 'pageTitle', 'Nouvelles demandes PDOs' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Decisionpdo::enattente':
                    $this->set( 'pageTitle', 'PDOs en attente' );
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
            $pdos = $this->Dossier->find( 'all', $params );


            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'pdos' ) );
        }
    }
?>