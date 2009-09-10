<?php
    class RelancesController extends AppController
    {

        var $name = 'Relances';
        var $uses = array( 'Orientstruct', 'Relance', 'Option', 'Personne', 'Structurereferente' );

        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'statutrelance', $this->Option->statutrelance() );
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
            $this->assert( !empty( $statutRelance ), 'invalidParameter' );

            $this->Relance->create( $this->data );
            $comparators = array( '<' => '<' ,'>' => '>','<=' => '<=', '>=' => '>=' );
            $cmp = Set::extract( $this->data, 'Relance.compare' );
            $this->assert( empty( $cmp ) || in_array( $cmp, array_keys( $comparators ) ), 'invalidParameter' );

            if( !empty( $this->data ) && $this->Relance->validates()  ) {
                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

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

                $options = $this->Relance->search( $statutRelance, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

//                 $options['fields'][] = 'Orientstruct.daterelance';
//                 $options['fields'][] = 'Orientstruct.statutrelance';
                $orientsstructs = $this->Orientstruct->find( 'all', $options );

                $this->Dossier->commit();

                $this->set( 'orientsstructs', $orientsstructs );
            }


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
    }
?>
