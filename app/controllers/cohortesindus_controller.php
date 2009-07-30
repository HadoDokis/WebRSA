<?php
    App::import('Sanitize');

    class CohortesindusController extends AppController
    {
        var $name = 'Cohortesindus';
        var $uses = array( 'Cohorteindu', 'Option',  'Structurereferente', 'Infofinanciere' );

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );

        function __construct() {
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        function beforeFilter() {
            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );


            $return = parent::beforeFilter();
                $this->set( 'natpfcre', $this->Option->natpfcre() );
                $this->set( 'typeparte', $this->Option->typeparte() );
                $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );


                $this->set( 'dif', $this->Option->dif() );
            return $return;
        }

        function index() {

            $params = $this->data;

            if( !empty( $params ) ) {
                /**
                *
                * Sauvegarde
                *
                */

                // On a renvoyé  le formulaire de la cohorte
                if( !empty( $this->data['Infofinanciere'] ) ) {
                    $valid = $this->Dossier->Infofinanciere->saveAll( $this->data['Infofinanciere'], array( 'validate' => 'only', 'atomic' => false ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Dossier->Infofinanciere->saveAll( $this->data['Infofinanciere'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            // FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Infofinanciere.{n}.dossier_id' ) ) as $dossier_id ) {
                                $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                            }
                            $this->Dossier->commit();
                        }
                        else {
                            $this->Dossier->rollback();
                        }
                    }
                }
                /**
                *
                * Filtrage
                *
                */
                else {
                    $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                    $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                    $this->Dossier->begin(); // Pour les jetons

                    $this->paginate = $this->Cohorteindu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                    $this->paginate['limit'] = 10;
                    $cohorteindu = $this->paginate( 'Infofinanciere' );

                    $this->Dossier->commit();


                    $this->set( 'cohorteindu', $cohorteindu );

                    $this->data['Search'] = $params;

                }
            }
        }
    }
?>