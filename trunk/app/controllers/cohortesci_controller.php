<?php
    App::import('Sanitize');

    class CohortesciController extends AppController
    {
        var $name = 'Cohortesci';
        var $uses = array( 'Cohorteci', 'Option', 'Contratinsertion', 'Typeorient', 'Orientstruct', 'Accoemploi', 'Adresse', 'Serviceinstructeur', 'Suiviinstruction' );

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );

        function __construct() {
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );
            $this->set( 'printed', $this->Option->printed() );
            $this->set( 'decision_ci', $this->Option->decision_ci() );
            return $return;
        }

        function index() {
//             $this->assert( !empty( $statutCI ), 'error404' );

            $typeservice = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'typeservice', $typeservice );

            $params = $this->data;
//             debug( $params );
            if( !empty( $params ) ) {
                /**
                *
                * Sauvegarde
                *
                */

                // On a renvoyé  le formulaire de la cohorte
                if( !empty( $this->data['Contratinsertion'] ) ) {
// debug( $this->data['Contratinsertion'] );
                    $valid = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $this->data['Contratinsertion'], array( 'validate' => 'only', 'atomic' => false ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $this->data['Contratinsertion'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            // FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Contratinsertion.{n}.dossier_id' ) ) as $dossier_id ) {
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

                    $this->paginate = $this->Cohorteci->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                    $cohorteci = $this->paginate( 'Contratinsertion' );

                    $this->Dossier->commit();

                    foreach( $cohorteci as $key => $value ) {
                        if( $value['Contratinsertion']['decision_ci'] == 'E' ) {
                            $cohorteci[$key]['Contratinsertion']['proposition_decision_ci'] = 'V';
                        }
                        else {
                            $cohorteci[$key]['Contratinsertion']['proposition_decision_ci'] = $value['Contratinsertion']['decision_ci'];
                        }

                        if( empty( $value['Contratinsertion']['datevalidation_ci'] ) ) {
                            $cohorteci[$key]['Contratinsertion']['proposition_datevalidation_ci'] = date( 'Y-m-d' );
                        }
                        else {
                            $cohorteci[$key]['Contratinsertion']['proposition_datevalidation_ci'] = $value['Contratinsertion']['datevalidation_ci'];
                        }
                    }

                    $this->set( 'cohorteci', $cohorteci );
                    $this->data['Search'] = $params;

                }
            }
        }
    }
?>