<?php
    App::import('Sanitize');

    class CriteresController extends AppController
    {
        var $name = 'Criteres';
        var $uses = array( /*'Critere',*/ 'Dossier', 'Foyer', 'Adresse', 'Typeorient', 'Structurereferente' );

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */
        function index() {

            $type = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    )
                )
            );
            $this->set( 'type', $type );

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

                // Critères sur un type d'orientation - libelle, parentid, modèle de notification
                $filtersTypeorient = array();
                foreach( array( 'lib_type_orient', 'parentid', 'modele_notif' ) as $critereTypeorient ) {
                    if( isset( $params['Typeorient'][$critereTypeorient] ) && !empty( $params['Typeorient'][$critereTypeorient] ) ) {
                        $filtersTypeorient['Typeorient.'.$critereTypeorient.' ILIKE'] = '%'.$params['Typeorient'][$critereTypeorient].'%';
                    }
                }

                // Critères sur une structure référente - libelle, nom_voie, ville, code_insee
                $filtersStruct = array();
                foreach( array( 'lib_struc', 'nom_voie', 'ville', 'code_insee' ) as $critereStruct ) {
                    if( isset( $params['Structurereferente'][$critereStruct] ) && !empty( $params['Typeorient'][$critereStruct] ) ) {
                        $filtersStruct['Structurereferente.'.$critereStruct.' ILIKE'] = '%'.$params['Structurereferente'][$critereStruct].'%';
                    }
                }


                // Recherche
                $this->Dossier->recursive = 2;
                $criteres = $this->paginate( 'Dossier', array( $filters ) );

                $this->set( 'criteres', $criteres );
                $this->data['Search'] = $params;
            }
        }


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
            else if( !empty( $this->params['structurereferente_id'] ) && is_numeric( $this->params['structurereferente_id'] ) ) {
                $struct = $this->Dossier->Foyer->Personne->Orientstruct->Structurereferente->find(
                    'first', array(
                        'conditions' => array(
                            'Structurereferente.id' => $this->params['structurereferente_id']
                        )
                    )
                );

                $this->assert( !empty( $struct ), 'error500' );

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

    }
?>
