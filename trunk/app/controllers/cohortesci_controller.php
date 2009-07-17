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
                    $conditions = array();
                    // INFO: seulement les personnes qui sont dans ma zone géographique
                    $conditions['Contratinsertion.personne_id'] = $this->Personne->findByZones( $this->Session->read( 'Auth.Zonegeographique' ), $this->Session->read( 'Auth.User.filtre_zone_geo' ) );

                    //Critère recherche par Contrat insertion: date de création contrat
                    if( dateComplete( $params, 'Cohorteci.date_saisi_ci' ) ) {
                        $date_saisi_ci = $params['Cohorteci']['date_saisi_ci'];
                        $conditions['Contratinsertion.date_saisi_ci'] = $date_saisi_ci['year'].'-'.$date_saisi_ci['month'].'-'.$date_saisi_ci['day'];
                    }

                    //Critère recherche par Contrat insertion: localisation de la personne rattachée au contrat
                    if( isset( $params['Cohorteci']['locaadr'] ) && !empty( $params['Cohorteci']['locaadr'] ) ){
                        $conditions[] = "Adresse.locaadr ILIKE '%".Sanitize::paranoid( $params['Cohorteci']['locaadr'] )."%'";
                    }

                    //Critère recherche par Contrat insertion: par décision du CG
                    if( isset( $params['Cohorteci']['decision_ci'] ) && !empty( $params['Cohorteci']['decision_ci'] ) ){
                        $conditions[] = "Contratinsertion.decision_ci ILIKE '%".Sanitize::paranoid( $params['Cohorteci']['decision_ci'] )."%'";
                    }

                    //Critère recherche par Contrat insertion: date de validation du contrat
                    if( dateComplete( $params, 'Cohorteci.datevalidation_ci' ) ) {
                        $datevalidation_ci = $params['Cohorteci']['datevalidation_ci'];
                        $conditions['Contratinsertion.datevalidation_ci'] = $datevalidation_ci['year'].'-'.$datevalidation_ci['month'].'-'.$datevalidation_ci['day'];
                    }

                    //Critère recherche par Contrat insertion: par service instructeur
                    if( isset( $params['Cohorteci']['serviceinstructeur_id'] ) && !empty( $params['Cohorteci']['serviceinstructeur_id'] ) ){
                        $conditions['Serviceinstructeur.id'] = $params['Cohorteci']['serviceinstructeur_id'];
                    }


                    $this->Contratinsertion->unbindModelAll();
                    $this->Contratinsertion->bindModel(
                        array(
                            'belongsTo' => array(
                                'Personne' => array(
                                    'foreignKey' => false,
                                    'conditions' => array( 'Contratinsertion.personne_id = Personne.id' )
                                ),
                                'Adressefoyer' => array(
                                    'foreignKey' => false,
                                    'conditions' => array(
                                        'Adressefoyer.foyer_id = Personne.foyer_id',
                                        'Adressefoyer.rgadr = \'01\''
                                    )
                                ),
                                'Adresse' => array(
                                    'foreignKey' => false,
                                    'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                                ),
                                'Foyer' => array(
                                    'foreignKey' => false,
                                    'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id' )
                                ),
                                'Dossier' => array(
                                    'foreignKey' => false,
                                    'conditions' => array( 'Dossier.id = Foyer.dossier_rsa_id' )
                                ),
                                'Serviceinstructeur' => array(
                                    'foreignKey' => false,
                                    'conditions' => array( 'Serviceinstructeur.id' => $this->Session->read( 'Auth.User.serviceinstructeur_id' ) )
                                )
                            )
                        )
                    );

                    $contrats = $this->Contratinsertion->find( 'all', array( 'conditions' => array( $conditions ), 'recursive' => 0 ) );

                    foreach( $contrats as $key => $value ) {
                        if( empty( $value['Contratinsertion']['decision_ci'] ) ) {
                            $contrats[$key]['Contratinsertion']['proposition_decision_ci'] = 'V';
                            $contrats[$key]['Contratinsertion']['proposition_datevalidation_ci'] = date( 'Y-m-d' );
                        }
                        else {
                            $contrats[$key]['Contratinsertion']['proposition_decision_ci'] = $value['Contratinsertion']['decision_ci'];
                            $contrats[$key]['Contratinsertion']['proposition_datevalidation_ci'] = $value['Contratinsertion']['datevalidation_ci'];
                        }
                    }

    // debug( $contrats );

                    $this->set( 'contrats', $contrats );

                    $this->data['Search'] = $params;

                }
            }
        }
    }
?>