<?php
    App::import('Sanitize');

    class CriteresController extends AppController
    {
        var $name = 'Criteres';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Typeorient', 'Structurereferente', 'Option');
        var $aucunDroit = array('index', 'menu', 'constReq');

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */
        function constReq ($requete, $champ, $valeur) {
            if (empty($requete))
                return "($champ = $valeur)";
            else
                return $requete." AND ($champ = $valeur) ";
        }  

        function index() {

            $this->set( 'typeorient', $this->Typeorient->listOptions() );
            $this->set( 'typestruct', $this->Structurereferente->list1Options() );
            $this->set( 'statuts', $this->Option->statut_orient() );

            $params = $this->data;

            if( count( $params ) > 0 ) {
                $filters = array();
                $requete = '';
                $select  = "SELECT * FROM personnes WHERE id IN ( SELECT personne_id FROM orientsstructs WHERE ";

                // Critères sur le dossier - date de demande
                if( isset( $params['Dossier']['dtdemrsa'] ) && !empty( $params['Dossier']['dtdemrsa'] ) ) {
                    $valid_from = ( valid_int( $params['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['day'] ) );
                    $valid_to = ( valid_int( $params['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['day'] ) );
                    if( $valid_from && $valid_to ) {
                        $filters[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $params['Dossier']['dtdemrsa_from']['year'], $params['Dossier']['dtdemrsa_from']['month'], $params['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $params['Dossier']['dtdemrsa_to']['year'], $params['Dossier']['dtdemrsa_to']['month'], $params['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
                    }
                }
 
                // Critères sur un type d'orientation - libelle, parentid, modèle de notification
                if( isset( $params['Typeorient']['id'] ) && !empty( $params['Typeorient']['id'] ) )
                    $requete = $this->constReq($requete, 'Orientsstructs.typeorient_id', $params['Typeorient']['id']);

                // Critères sur une structure référente - libelle, nom_voie, ville, code_insee
                if( isset( $params['Structurereferente']['id'] ) && !empty( $params['Structurereferente']['id'] ))
                    $requete = $this->constReq($requete, 'Orientsstructs.structurereferente_id', $params['Structurereferente']['id']);

                // Critères sur une statut d'orientation
                if( isset( $params['Orientstructs']['statut_orient']  ) && !empty( $params['Orientstructs']['statut_orient'] ))
                    $requete = $this->constReq($requete, 'Orientsstructs.statut_orient', "'".$params['Orientstructs']['statut_orient']."'");
           
                $requete = $select. $requete .')'; 
                $criteres = $this->Personne->query($requete);
                // Recherche
                for ($i = 0; $i < count ($criteres); $i++ ){
                    $criteres[$i]['Foyer'] = $this->Foyer->read(null, $criteres[$i][0]['foyer_id']);
                    $criteres[$i]['Dossier'] = $this->Dossier->read(null, $criteres[$i]['Foyer']['Foyer']['dossier_rsa_id']);
 
                }
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
