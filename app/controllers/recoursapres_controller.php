<?php
    App::import('Sanitize');

    class RecoursapresController extends AppController
    {
        var $name = 'Recoursapres';
        var $uses = array( 'Canton', 'Dossier', 'Recoursapre', 'Foyer', 'Adresse', 'Comiteapre', 'Personne', 'ApreComiteapre', 'Apre', 'Option' );

        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        /**
        */


        function beforeFilter() {
            $return = parent::beforeFilter();
            $options = array(
                'decisioncomite' => array(
                    'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
                    'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
                    'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
                ),
                'recoursapre' => array(
                    'N' => __d( 'apre', 'ENUM::RECOURSAPRE::N', true ),
                    'O' => __d( 'apre', 'ENUM::RECOURSAPRE::O', true )
                )
            );
            $this->set( 'options', $options );

            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function demande() {
            $this->_index( 'Recoursapre::demande' );
        }

        //---------------------------------------------------------------------

        function visualisation() {
            $this->_index( 'Recoursapre::visualisation' );
        }
        /** ********************************************************************
        *
        *** *******************************************************************/

        function _index( $avisRecours = null ){
            $this->set( 'comitesapre', $this->Comiteapre->find( 'list' ) );

            $this->Dossier->begin(); // Pour les jetons
            if( !empty( $this->data ) ) {
                if( !empty( $this->data['ApreComiteapre'] ) ) {
                    $data = Set::extract( $this->data, '/ApreComiteapre' );
                    if( $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                        $saved = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
                            $this->ApreComiteapre->commit();
                            $this->redirect( array( 'action' => 'demande' ) ); // FIXME
                        }
                        else {
                            $this->ApreComiteapre->rollback();
                        }
                    }
                }


                $recoursapres = $this->Recoursapre->search( $avisRecours, $this->data );
                $recoursapres['limit'] = 10;
                $this->paginate = $recoursapres;
                $recoursapres = $this->paginate( 'ApreComiteapre' );

                $this->set( 'recoursapres', $recoursapres );

                $this->Dossier->commit();

            }

            switch( $avisRecours ) {
                case 'Recoursapre::demande':
                    $this->set( 'pageTitle', 'Demandes de recours' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Recoursapre::visualisation':
                    $this->set( 'pageTitle', 'Visualisation des recours' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }

            $this->Dossier->commit(); //FIXME
        }

        /// Export du tableau en CSV
        function exportcsv( $action = 'all' ) {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Recoursapre->search( "Recoursapre::{$action}", $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $apres = $this->Apre->find( 'all', $querydata );

            $this->layout = '';
            $this->set( compact( 'apres' ) );

            switch( $action ) {
                case 'all':
                    $this->render( $this->action, null, 'exportcsv' );
                    break;
                default:
                    $this->render( $this->action, null, 'exportcsveligible' );
            }
        }
    }
?>