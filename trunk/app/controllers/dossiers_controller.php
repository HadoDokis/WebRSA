<?php
    App::import('Sanitize');

    class DossiersController extends AppController
    {
        var $name = 'Dossiers';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Structurereferente', 'Orientstruct', 'Typeorient', 'Contratinsertion', 'Detaildroitrsa', 'Option', 'Dspp', 'Dspf', 'Infofinanciere', 'Creance' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'natpf', $this->Option->natpf() );
            $this->set( 'decision_ci', $this->Option->decision_ci() );
        }
        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */
        function index() {
            $params = $this->data;

            if( count( $params ) > 0 ) {
                $filters = array();

                // Critères sur le dossier - numéro de dossier
                if( isset( $params['Dossier']['numdemrsa'] ) && !empty( $params['Dossier']['numdemrsa'] ) ) {
                    $filters[] = "Dossier.numdemrsa ILIKE '%".Sanitize::paranoid( $params['Dossier']['numdemrsa'] )."%'";
                }

                // Critères sur le dossier - date de demande
                if( isset( $params['Dossier']['dtdemrsa'] ) && !empty( $params['Dossier']['dtdemrsa'] ) ) {
                    $valid_from = ( valid_int( $params['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['day'] ) );
                    $valid_to = ( valid_int( $params['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['day'] ) );
                    if( $valid_from && $valid_to ) {
                        $filters[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $params['Dossier']['dtdemrsa_from']['year'], $params['Dossier']['dtdemrsa_from']['month'], $params['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $params['Dossier']['dtdemrsa_to']['year'], $params['Dossier']['dtdemrsa_to']['month'], $params['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
                    }
                }

                // Critères sur une personne du foyer - nom, prénom, nom de jeune fille
                $filtersPersonne = array();
                foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                    if( isset( $params['Personne'][$criterePersonne] ) && !empty( $params['Personne'][$criterePersonne] ) ) {
                        $filtersPersonne['Personne.'.$criterePersonne.' ILIKE'] = '%'.$params['Personne'][$criterePersonne].'%';
                    }
                }

                // Critères sur une personne du foyer - date de naissance
                if( isset( $params['Personne']['dtnai'] ) && !empty( $params['Personne']['dtnai'] ) ) {
                    if( valid_int( $params['Personne']['dtnai']['year'] ) ) {
                        $filtersPersonne['EXTRACT(YEAR FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['year'];
                    }
                    if( valid_int( $params['Personne']['dtnai']['month'] ) ) {
                        $filtersPersonne['EXTRACT(MONTH FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['month'];
                    }
                    if( valid_int( $params['Personne']['dtnai']['day'] ) ) {
                        $filtersPersonne['EXTRACT(DAY FROM Personne.dtnai) ='] = $params['Personne']['dtnai']['day'];
                    }
                }

                // Recherche des foyers suivant les critères sur les personnes
                if( count( $filtersPersonne ) > 0 ) {
                    $foyers = $this->Personne->find(
                        'list',
                        array(
                            'fields' => array(
                                'Personne.id',
                                'Personne.foyer_id',
                            ),
                            'conditions' => array( $filtersPersonne ),
                            'recursive' => -1
                        )
                    );
                    // Critères sur les dossiers suivant les numéros de foyers retournés
                    $filters[] = ( count( $foyers ) > 0 ) ? 'Foyer.id IN ( '.implode( ',', $foyers ).' )' : 'FALSE';
                }

                // Recherche
                $this->Dossier->recursive = 2;
                $dossiers = $this->paginate( 'Dossier', array( $filters ) );

                $this->set( 'dossiers', $dossiers );
                $this->data['Search'] = $params;


            }
        }

        /**
        */
        function menu() {
            // Ce n'est pas un appel par une URL
            $this->assert( isset( $this->params['requested'] ), 'error404' );

            $conditions = array();

            if( !empty( $this->params['id'] ) && is_numeric( $this->params['id'] ) ) {
                $conditions['"Dossier"."id"'] = $this->params['id'];
            }
            else if( !empty( $this->params['foyer_id'] ) && is_numeric( $this->params['foyer_id'] ) ) {
                $conditions['"Foyer"."id"'] = $this->params['foyer_id'];
            }
            else if( !empty( $this->params['personne_id'] ) && is_numeric( $this->params['personne_id'] ) ) {
                $personne = $this->Dossier->Foyer->Personne->find(
                    'first', array(
                        'conditions' => array(
                            'Personne.id' => $this->params['personne_id']
                        )
                    )
                );

                $this->assert( !empty( $personne ), 'error500' );

                $conditions['"Foyer"."id"'] = $personne['Personne']['foyer_id'];
            }

            $this->assert( !empty( $conditions ), 'error500' );

            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => $conditions,
                    'recursive'  => 2
                )
            );

            $this->assert( !empty( $dossier ), 'error500' );

            usort( $dossier['Foyer']['AdressesFoyer'], create_function( '$a,$b', 'return strcmp( $a["rgadr"], $b["rgadr"] );' ) );

            foreach( $dossier['Foyer']['AdressesFoyer'] as $key => $AdressesFoyer ) {
                $adresses = $this->Adresse->find(
                    'all',
                    array(
                        'conditions' => array(
                            'Adresse.id' => $AdressesFoyer['adresse_id'] )
                    )
                );
                $dossier['Foyer']['AdressesFoyer'][$key] = array_merge( $dossier['Foyer']['AdressesFoyer'][$key], $adresses[0] );
            }

            return $dossier;
        }

        /**
        */
        function view( $id = null ) {
            $this->assert( valid_int( $id ), 'error404' );

            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array( 'Dossier.id' => $id ),
                    'recursive' => 2
                )
            );

            $adresse = $this->Adresse->find(
                'first',
                array(
                    'conditions' => array( 'Adresse.id' => $id ),
                    'recursive' => 2
                )
            );
            $this->set( 'adresse', $adresse );

            $orient = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array( 'Orientstruct.id' => $id ),
                    'recursive' => 2
                )
            );
            $this->set( 'orient', $orient );

            $contrat = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array( 'Contratinsertion.id' => $id ),
                    'recursive' => 2
                )
            );
            $this->set( 'contrat', $contrat );


            $detaildroitrsa = $this->Detaildroitrsa->find(
                'first',
                array(
                    'conditions' => array( 'Detaildroitrsa.id' => $id ),
                    'recursive' => 2
                )
            );
            $this->set( 'detaildroitrsa', $detaildroitrsa );

            $infofinance = $this->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array( 'Infofinanciere.id' => $id ),
                    'recursive' => 2
                )
            );
            $this->set( 'infofinance', $infofinance );

            $creance = $this->Creance->find(
                'first',
                array(
                    'conditions' => array( 'Creance.id' => $id ),
                    'recursive' => 2
                )
            );
            $this->set( 'creance', $creance );

            $this->assert( !empty( $dossier ), 'error404' );
            $this->set( 'dossier', $dossier );

//////////////////////////////////////
            $dataDos = array();

            // DSP foyer
            $dataDos['foyer'] = $this->Dspf->find(
                'first',
                array(
                    'conditions' => array(
                        'Dspf.foyer_id' => $id
                    )
                )
            ) ;

            // Ajout des DSP demandeur et conjoint
            foreach( array( 'DEM', 'CJT' ) as $rolepers ) {
                $dataDos[$rolepers] = array();
                $personne = $this->Personne->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Personne.foyer_id' => $id,
                            'Personne.rolepers' => $rolepers,
                        ),
                        'recursive' => -1
                    )
                ) ;
                if( !empty( $personne ) ) {
                    $dataDos[$rolepers] = $personne;
                    $dataDos_personne = $this->Dspp->find(
                        'first',
                        array(
                            'conditions' => array(
                                'Dspp.personne_id' => $personne['Personne']['id']
                            ),
                            'recursive' => 2
                        )
                    ) ;
                    if( !empty( $dataDos_personne ) ) {
                        $dataDos[$rolepers] = array_merge( $dataDos[$rolepers], $dataDos_personne );
                    }
                }
            }

            $this->set( 'dataDos', $dataDos );
            $this->set( 'foyer_id', $id );
        }
    }
?>
