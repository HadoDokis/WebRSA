<?php
    class CommissionsapreController extends AppController
    {

        var $name = 'Commissionsapre';
        var $uses = array( 'Canton', 'Apre', 'Apre', 'Option', 'Personne', 'Structurereferente', 'Commissionapre' );
        var $helpers = array( 'Csv', 'Paginator', 'Locale' );
        /**
        *
        *
        *
        */

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );

        function __construct() {
//             $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'commissionapre' ) ) ) );
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        function beforeFilter() {
            $return = parent::beforeFilter();
//             $this->set( 'statutrelance', $this->Option->statutrelance() );
//             $this->set( 'printed', $this->Option->printed() );
            return $return;
        }

        /**
        *
        *
        *
        */

        //*********************************************************************

        function nouvelles() {
            $this->_index( 'Commissionapre::nouvelles' );
        }

        //---------------------------------------------------------------------

        function enattente() {
            $this->_index( 'Commissionapre::enattente' );
        }
        //---------------------------------------------------------------------

        function valide() {
            $this->_index( 'Commissionapre::valide' );
        }
        //---------------------------------------------------------------------


        function _index( $statutCommissionapre = null ){
            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }

            $this->assert( !empty( $statutCommissionapre ), 'invalidParameter' );
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

//             $this->Commissionapre->create( $this->data );


            if( !empty( $this->data )  ) {
                if( !empty( $this->data['Apre'] ) ) {
                    $valid = $this->Apre->saveAll( $this->data['Apre'], array( 'validate' => 'only', 'atomic' => false ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Apre->saveAll( $this->data['Apre'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            //FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Apre.{n}.dossier_id' ) ) as $dossier_id ) {
                                $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                            }
                            $this->Dossier->commit();
                            $this->data['Apre'] = array();

                        }
                        else {
                            $this->Dossier->rollback();
                        }
                    }
                }



                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Commissionapre->search( $statutCommissionapre, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $commissionapre = $this->paginate( 'Apre' );


                $this->Dossier->commit();

                $this->set( 'commissionapre', $commissionapre );
            }

            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );

            switch( $statutCommissionapre ) {
                case 'Commissionapre::nouvelles':
                    $this->set( 'pageTitle', 'Nouvelles demandes Commission' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Commissionapre::enattente':
                    $this->set( 'pageTitle', 'Avis en attente' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Commissionapre::valide':
                    $this->set( 'pageTitle', 'Avis de la commission validé' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }
        }

        /** ********************************************************************
        *
        *** *******************************************************************/
/*
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $_limit = 10;
            $params = $this->Commissionapre->search( 'Commissionapre::relance', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );

            unset( $params['limit'] );
            $orients = $this->Apre->find( 'all', $params );


            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'orients' ) );
        }*/
    }
?>
