<?php
    App::import('Sanitize');

    class CriteresapresController extends AppController
    {
        public $name = 'Criteresapres';

        public $uses = array( 'Canton', 'Critereapre', 'Apre', 'Tiersprestataireapre', 'Option', 'Zonegeographique' );

        public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml', 'Xpaginator' );

        /**
        *
        */

        public function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'all', 'eligible' ) ) ) );
            parent::__construct();
        }

        /**
        *
        */

        protected function _setOptions() {
            $options = $this->Apre->allEnumLists();
            $this->set( 'options', $options );
            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );

            /// Liste des tiers prestataires
            $this->set( 'tiers', $this->Tiersprestataireapre->find( 'list' ) );
        }


        /**
        *
        */

        public function all() {
            $this->_index( 'Critereapre::all' );
        }

        /**
        *
        */

        public function eligible() {
            $this->_index( 'Critereapre::eligible' );
        }

        /**
        *
        */

        public function _index( $etatApre = null ){
            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }

            $this->assert( !empty( $etatApre ), 'invalidParameter' );
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {
                $this->Critereapre->begin(); // Pour les jetons

                $queryData = $this->Critereapre->search( $etatApre, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

                $this->paginate = $queryData;
                $this->paginate['limit'] = 10;
                $apres = $this->paginate( 'Apre' );

                ///
                unset( $queryData['fields'] );
                $queryData['recursive'] = -1;


                $joins = array(
                    array(
                        'table'      => 'apres_comitesapres',
                        'alias'      => 'ApreComiteapre',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'ApreComiteapre.apre_id = Apre.id' )
                    ),
                    array(
                        'table'      => 'comitesapres',
                        'alias'      => 'Comiteapre',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'ApreComiteapre.comiteapre_id = Comiteapre.id'
                        )
                    ),
                );

                $queryData['joins'] = array_merge( $queryData['joins'], $joins );


                ///Nb d'APREs appartenant à un comité et dont la décision a été/va être prise
                $attenteDecision = array(
                    'conditions' => array(
                        'ApreComiteapre.apre_id IS NOT NULL',
                        'ApreComiteapre.decisioncomite IS NULL'
                    )
                );
                $attenteDecisionsApres = $this->Apre->find(
                    'count',
                    Set::merge( $queryData, $attenteDecision )
                );

                ///Nb d'APREs en attente de traitement(n'appartenant à aucun comité et n'ayant aucune décision de prise)
                $attenteTraitement = array(
                    'conditions' => array(
                        'ApreComiteapre.apre_id IS NULL'
                    )
                );
                $attenteTraitementApres = $this->Apre->find(
                    'count',
                    Set::merge( $queryData, $attenteTraitement )
                );


                $this->set( 'attenteDecisionsApres', $attenteDecisionsApres );
                $this->set( 'attenteTraitementApres', $attenteTraitementApres );

                $this->set( 'apres', $apres );

                $this->Critereapre->commit();
            }

            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
            $this->_setOptions();
            switch( $etatApre ) {
                case 'Critereapre::all':
                    $this->set( 'pageTitle', 'Toutes les APREs' );
                    $statutApre = Set::classicExtract( $this->data, 'Filtre.statutapre' );
                    if( $statutApre == 'F' ) {
                        $this->render( $this->action, null, 'forfaitaire' );
                    }
                    else {
                        $this->render( $this->action, null, 'formulaire' );
                    }
                    break;
                case 'Critereapre::forfaitaire':
                    $this->set( 'pageTitle', 'APREs forfaitaires' );
                    $this->render( $this->action, null, 'forfaitaire' );
                    break;
                case 'Critereapre::eligible':
                    $this->set( 'pageTitle', 'Eligibilité des APREs' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }

        }

        /**
        * Export du tableau en CSV
        */

        public function exportcsv( $action = 'all' ) {
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
            $this->_setOptions();
        }
    }
?>