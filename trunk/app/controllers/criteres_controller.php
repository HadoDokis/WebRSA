<?php
    App::import('Sanitize');

    class CriteresController extends AppController
    {
        var $name = 'Criteres';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur');
        //var $aucunDroit = array('index', 'menu', 'constReq');
        var $aucunDroit = array( 'constReq' );

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
	       $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );

            $this->set( 'typeorient', $this->Typeorient->listOptions() );
//             $this->set( 'typestruct', $this->Structurereferente->list1Options() );
            $this->set( 'statuts', $this->Option->statut_orient() );
            $this->set( 'statuts_contrat', $this->Option->statut_contrat_insertion() );
            $this->set( 'services_instructeur', $this->Serviceinstructeur->listOptions());

            $params = $this->data;
            if( count( $params ) > 0 ) {
                // INFO: seulement les personnes qui sont dans ma zone géographique
                $mesZones = $this->Personne->findByZones( $this->Session->read( 'Auth.Zonegeographique' ) );
                $requete = ( !empty( $mesZones ) ? 'personne_id IN ('.implode( ',', array_values( $mesZones ) ).') ' : 'personne_id IS NULL' );
                $select  = "SELECT * FROM personnes WHERE id IN ( SELECT personne_id FROM orientsstructs WHERE ";

                //Critères sur un type d'orientation - libelle, parentid, modèle de notification
                if( isset( $params['Typeorient']['id'] ) && !empty( $params['Typeorient']['id'] ) )
                    $requete = $this->constReq($requete, 'Orientsstructs.typeorient_id', suffix( $params['Typeorient']['id'] ) );

                //Critères sur une structure référente - libelle, nom_voie, ville, code_insee
                if( isset( $params['Structurereferente']['id'] ) && !empty( $params['Structurereferente']['id'] ))
                    $requete = $this->constReq($requete, 'Orientsstructs.structurereferente_id', suffix( $params['Structurereferente']['id'] ) );


                //Critères sur une statut d'orientation
                if( isset( $params['Orientstructs']['statut_orient']  ) && !empty( $params['Orientstructs']['statut_orient'] ))
                    $requete = $this->constReq($requete, 'Orientsstructs.statut_orient', "'".$params['Orientstructs']['statut_orient']."'");



                $requete = $select. $requete .')';
                $criteres = $this->Personne->query($requete);



	    /************************************** Debut Requête pour rechercher par Contrat insertion  ****************************************/
                // Critères sur un contrat insertion - date de debut
                /*$filtersContrat = array();
                foreach( array( 'dd_ci' ) as $critereContrat ) {
                    if( isset( $params['Contratinsertion'][$critereContrat] ) && !empty( $params['Contratinsertion'][$critereContrat] ) ) {
                        $filtersContrat['Contratinsertion.'.$critereContrat.' ILIKE'] = '%'.$params['Contratinsertion'][$critereContrat].'%';
                    }
                }

                // Critères sur un contrat insertion - date de debut
                if( isset( $params['Contratinsertion']['dd_ci'] ) && !empty( $params['Contratinsertion']['dd_ci'] ) ) {
                    if( valid_int( $params['Contratinsertion']['dd_ci']['year'] ) ) {
                        $filtersContrat['EXTRACT(YEAR FROM Contratinsertion.dd_ci) ='] = $params['Contratinsertion']['dd_ci']['year'];
                    }
                    if( valid_int( $params['Contratinsertion']['dd_ci']['month'] ) ) {
                        $filtersContrat['EXTRACT(MONTH FROM Contratinsertion.dd_ci) ='] = $params['Contratinsertion']['dd_ci']['month'];
                    }
                    if( valid_int( $params['Contratinsertion']['dd_ci']['day'] ) ) {
                        $filtersContrat['EXTRACT(DAY FROM Contratinsertion.dd_ci) ='] = $params['Contratinsertion']['dd_ci']['day'];
                    }
                }*/
	    /************************************** Fin de la Requête pour rechercher par Contrat insertion  ****************************************/

                //Recherche

                for ($i = 0; $i < count ($criteres); $i++ ){
                    $criteres[$i]['Foyer'] = $this->Foyer->read(null, $criteres[$i][0]['foyer_id']);
                    $criteres[$i]['Dossier'] = $this->Dossier->read(null, $criteres[$i]['Foyer']['Foyer']['dossier_rsa_id']);
                }
                $this->set( 'criteres', $criteres );

                $this->data['Search'] = $params;
            }
        }
    }
?>
