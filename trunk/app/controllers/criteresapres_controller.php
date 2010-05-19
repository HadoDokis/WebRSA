<?php
    App::import('Sanitize');

    class CriteresapresController extends AppController
    {
        var $name = 'Criteresapres';
        var $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Critereapre', 'Apre', 'Tiersprestataireapre', 'Option',  );

        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml', 'Xpaginator' );
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

            /// Liste des tiers prestataires
            $this->set( 'tiers', $this->Tiersprestataireapre->find( 'list' ) );

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

/*
            foreach( $this->Apre->modelsFormation as $model ) {
                $aides = $this->Apre->{$model}->find(
                    'list',
                    array(
                        'fields' => array(
                           "$model.tiersprestataireapre_id"
                        )
                    )
                );
                $this->set( 'aides', $aides );

            }*/






            $this->assert( !empty( $etatApre ), 'invalidParameter' );
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {
// debug($params);
                $this->Dossier->begin(); // Pour les jetons

                $queryData = $this->Critereapre->search( $etatApre, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

                $this->paginate = $queryData;
                $this->paginate['limit'] = 10;
                $apres = $this->paginate( 'Apre' );

                ///
                unset( $queryData['fields'] );
                $queryData['recursive'] = -1;


                $queryData['joins'] = array(
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

                $this->Dossier->commit();
            }

            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );

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