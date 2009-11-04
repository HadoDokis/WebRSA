<?php
    class RelancesController extends AppController
    {

        var $name = 'Relances';
        var $uses = array( 'Canton', 'Orientstruct', 'Relance', 'Option', 'Personne', 'Structurereferente' );
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
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'relance' ) ) ) );
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'statutrelance', $this->Option->statutrelance() );
            $this->set( 'printed', $this->Option->printed() );
            return $return;
        }

        /**
        *
        *
        *
        */

        //*********************************************************************

        function arelancer() {
            $this->_index( 'Relance::arelancer' );
        }

        //---------------------------------------------------------------------

        function relance() {
            $this->_index( 'Relance::relance' );
        }
        //---------------------------------------------------------------------


        function _index( $statutRelance = null ){
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

            $this->assert( !empty( $statutRelance ), 'invalidParameter' );
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $this->Relance->create( $this->data );
            $comparators = array( '<' => '<' ,'>' => '>','<=' => '<=', '>=' => '>=' );
            $cmp = Set::extract( $this->data, 'Relance.compare' );
            $this->assert( empty( $cmp ) || in_array( $cmp, array_keys( $comparators ) ), 'invalidParameter' );

            if( !empty( $this->data ) && $this->Relance->validates()  ) {
// debug( $this->data['Orientstruct'] );
                if( !empty( $this->data['Orientstruct'] ) ) {
                    $valid = $this->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'only', 'atomic' => false ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            //FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Orientstruct.{n}.dossier_id' ) ) as $dossier_id ) {
                                $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                            }
                            $this->Dossier->commit();
                            $this->data['Orientstruct'] = array();

                        }
                        else {
                            $this->Dossier->rollback();
                        }
                    }
                }



                $this->Dossier->begin(); // Pour les jetons




                $this->paginate = $this->Relance->search( $statutRelance, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $orientsstructs = $this->paginate( 'Orientstruct' );


                $this->Dossier->commit();

                $this->set( 'orientsstructs', $orientsstructs );
            }

            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
            switch( $statutRelance ){
                case 'Relance::arelancer':
                    $this->set( 'pageTitle', 'Dossiers à relancer' );
                    $this->render( $this->action, null, 'arelancer' );
                    break;
                case 'Relance::relance':
                    $this->set( 'pageTitle', 'Dossiers relancés' );
                    $this->render( $this->action, null, 'relance' );
                    break;
            }
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $_limit = 10;
            $params = $this->Relance->search( 'Relance::relance', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );

            unset( $params['limit'] );
            $orients = $this->Orientstruct->find( 'all', $params );


            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'orients' ) );
        }
    }
?>
