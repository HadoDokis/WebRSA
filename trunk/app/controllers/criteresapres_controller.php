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
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'all', 'eligible' ) ) ) );
            parent::__construct();
        }

        function beforeFilter() {
            $return = parent::beforeFilter();
            $options = $this->Apre->allEnumLists();
            $this->set( 'options', $options );
            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
            return $return;
        }


        //*********************************************************************

        function all() {
            $this->_index( 'Critereapre::all' );
        }

        //---------------------------------------------------------------------

//         function incomplete() {
//             $this->_index( 'Critereapre::incomplete' );
//         }
        //---------------------------------------------------------------------

        function eligible() {
            $this->_index( 'Critereapre::eligible' );
        }
        //---------------------------------------------------------------------

        function _index( $etatApre = null ){
            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }

            $this->assert( !empty( $etatApre ), 'invalidParameter' );
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Critereapre->search( $etatApre, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $apres = $this->paginate( 'Apre' );

                // Nb d'APREs présentes
                $countApre = count( Set::extract( $apres, '/Apre/id' ) );
                $this->set( 'countApre', $countApre );

                $this->Dossier->commit();

                $this->set( 'apres', $apres );

                ///Nb d'APREs appartenant à un comité et dont la décision a été/va être prise
                $attenteDecisionsApres = count( $this->Apre->find( 'all', array( 'conditions' => array( 'Apre.id IN ( SELECT apres_comitesapres.apre_id FROM apres_comitesapres WHERE apres_comitesapres.decisioncomite IS NULL )' ), 'recursive' => 0 ) ) );

                ///Nb d'APREs en attente de traitement(n'appartenant à aucun comité et n'ayant aucune décision de prise)
                $attenteTraitementApres = count( $this->Apre->find( 'all', array( 'conditions' => array( 'Apre.id NOT IN ( SELECT apres_comitesapres.apre_id FROM apres_comitesapres  )' ), 'recursive' => 0 ) ) );

                ///Nb d'APREs dont la décision a été prise
                $decisionsPrisesApres = count( $this->Apre->find( 'all', array( 'conditions' => array( 'Apre.id IN ( SELECT apres_comitesapres.apre_id FROM apres_comitesapres WHERE apres_comitesapres.decisioncomite IS NOT NULL )' ), 'recursive' => 0 ) ) );

                $this->set( 'attenteDecisionsApres', $attenteDecisionsApres );
                $this->set( 'attenteTraitementApres', $attenteTraitementApres );
                $this->set( 'decisionsPrisesApres', $decisionsPrisesApres );
// debug(count($attenteDecisionsApres));
//                 foreach( $apres as $apre ){
//                     $attenteDecision = Set::classicExtract( $apre, 'ApreComiteapre.apre_id' );
// 
//                     $decisionComite = Set::classicExtract( $apre, 'ApreComiteapre.decisioncomite' );
// 
//                     if( empty( $attenteDecision ) && empty( $decisionComite ) ){
// //                         $countDecision++;
//                         $countTraitement = $countApre - $countDecision;
//                     }
//                     else if( !empty( $attenteDecision ) && empty( $decisionComite ) ) {
//                         $countDecision++;
//                         $countTraitement = $countApre - $countDecision;
//                     }
//                     else if( !empty( $attenteDecision ) && !empty( $decisionComite ) ) {
//                         $countDecision = count( $decisionComite ) - $countDecision;
//                         $countTraitement = $countApre - count( $decisionComite );
//                     }
//                     $this->set( 'countDecision', $countDecision );
//                     $this->set( 'countTraitement', $countTraitement );
//                 }
// 


            }

            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );

            switch( $etatApre ) {
                case 'Critereapre::all':
                    $this->set( 'pageTitle', 'Toutes les APREs' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Critereapre::eligible':
                    $this->set( 'pageTitle', 'Eligibilite des APREs' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }
        }

        /// Export du tableau en CSV
        function exportcsv( $action = 'all' ) {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Critereapre->search( "Critereapre::{$action}", $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
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