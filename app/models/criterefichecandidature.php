<?php
    /**
    * Recherche de fiche de candidature (actioncandidat_personne)
    *
    * PHP versions 5
    *
    * @package       app
    * @subpackage    app.app.models
    */

    class Criterefichecandidature extends AppModel
    {
        var $name = 'Criterefichecandidature';
        var $useTable = false;


        public function search( $criteresfichescandidature ) {
            /// Conditions de base

            $conditions = array();

            if ( isset($criteresfichescandidature['ActioncandidatPersonne']['actioncandidat_id']) && !empty($criteresfichescandidature['ActioncandidatPersonne']['actioncandidat_id']) ) {
                $conditions[] = array('ActioncandidatPersonne.actioncandidat_id'=>$criteresfichescandidature['ActioncandidatPersonne']['actioncandidat_id']);
            }

            if ( isset($criteresfichescandidature['ActioncandidatPersonne']['referent_id']) && !empty($criteresfichescandidature['ActioncandidatPersonne']['referent_id']) ) {
                $conditions[] = array('ActioncandidatPersonne.referent_id'=>$criteresfichescandidature['ActioncandidatPersonne']['referent_id']);
            }

            if ( isset($criteresfichescandidature['Partenaire']['libstruc']) && !empty($criteresfichescandidature['Partenaire']['libstruc']) ) {
                $conditions[] = array('Partenaire.id'=>$criteresfichescandidature['Partenaire']['libstruc']);
            }

            if ( isset($criteresfichescandidature['ActioncandidatPersonne']['positionfiche']) && !empty($criteresfichescandidature['ActioncandidatPersonne']['positionfiche']) ) {
                $conditions[] = array('ActioncandidatPersonne.positionfiche'=>$criteresfichescandidature['ActioncandidatPersonne']['positionfiche']);
            }
            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresfichescandidature['Personne'][$criterePersonne] ) && !empty( $criteresfichescandidature['Personne'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresfichescandidature['Personne'][$criterePersonne] ).'\'';
                }
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersDossier = array();
            foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
                if( isset( $criteresfichescandidature['Dossier'][$critereDossier] ) && !empty( $criteresfichescandidature['Dossier'][$critereDossier] ) ) {
                    $conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$this->wildcard( $criteresfichescandidature['Dossier'][$critereDossier] ).'\'';
                }
            }

            /// Critères sur la date de signature de la fiche de candidature
            if( isset( $criteresfichescandidature['Dossier']['dtdemrsa'] ) && !empty( $criteresfichescandidature['Dossier']['dtdemrsa'] ) ) {
                $valid_from = ( valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_from']['day'] ) );
                $valid_to = ( valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $criteresfichescandidature['Dossier']['dtdemrsa_from']['year'], $criteresfichescandidature['Dossier']['dtdemrsa_from']['month'], $criteresfichescandidature['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresfichescandidature['Dossier']['dtdemrsa_to']['year'], $criteresfichescandidature['Dossier']['dtdemrsa_to']['month'], $criteresfichescandidature['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
                }
            }

            /// Critères sur la date de signature de la fiche de candidature
            if( isset( $criteresfichescandidature['ActioncandidatPersonne']['datesignature'] ) && !empty( $criteresfichescandidature['ActioncandidatPersonne']['datesignature'] ) ) {
                $valid_from = ( valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['year'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['month'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['day'] ) );
                $valid_to = ( valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['year'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['month'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'ActioncandidatPersonne.datesignature BETWEEN \''.implode( '-', array( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['year'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['month'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['year'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['month'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['day'] ) ).'\'';
                }
            }


            // Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
            if( $criteresfichescandidature['Dossier']['dernier'] ) {
                $conditions[] = 'Dossier.id IN (
                    SELECT
                            dossiers.id
                        FROM personnes
                            INNER JOIN prestations ON (
                                personnes.id = prestations.personne_id
                                AND prestations.natprest = \'RSA\'
                            )
                            INNER JOIN foyers ON (
                                personnes.foyer_id = foyers.id
                            )
                            INNER JOIN dossiers ON (
                                dossiers.id = foyers.dossier_id
                            )
                        WHERE
                            prestations.rolepers IN ( \'DEM\', \'CJT\' )
                            AND (
                                (
                                    nir_correct( Personne.nir )
                                    AND nir_correct( personnes.nir )
                                    AND personnes.nir = Personne.nir
                                    AND personnes.dtnai = Personne.dtnai
                                )
                                OR
                                (
                                    personnes.nom = Personne.nom
                                    AND personnes.prenom = Personne.prenom
                                    AND personnes.dtnai = Personne.dtnai
                                )
                            )
                        ORDER BY dossiers.dtdemrsa DESC
                        LIMIT 1
                )';
            }
            $joins = array(
                array(
                    'table'      => 'actionscandidats',
                    'alias'      => 'Actioncandidat',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Actioncandidat.id = ActioncandidatPersonne.actioncandidat_id' ),
                ),
                array(
                    'table'      => 'actionscandidats_partenaires',
                    'alias'      => 'ActioncandidatPartenaire',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'ActioncandidatPartenaire.actioncandidat_id = Actioncandidat.id' ),
                ),
                array(
                    'table'      => 'partenaires',
                    'alias'      => 'Partenaire',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'ActioncandidatPartenaire.partenaire_id = Partenaire.id' ),
                ),
                array(
                    'table'      => 'personnes',
                    'alias'      => 'Personne',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Personne.id = ActioncandidatPersonne.personne_id' ),
                ),
                array(
                    'table'      => 'foyers',
                    'alias'      => 'Foyer',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                ),
                array(
                    'table'      => 'dossiers',
                    'alias'      => 'Dossier',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
                ),
                array(
                    'table'      => 'referents',
                    'alias'      => 'Referent',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Referent.id = ActioncandidatPersonne.referent_id' ),
                ),
                array(
                    'table'      => 'structuresreferentes',
                    'alias'      => 'Structurereferente',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Structurereferente.id = Referent.structurereferente_id' ),
                )
            );


            $query = array(
                'fields' => array(
                    'ActioncandidatPersonne.id',
                    'ActioncandidatPersonne.actioncandidat_id',
                    'ActioncandidatPersonne.personne_id',
                    'ActioncandidatPersonne.referent_id',
                    'ActioncandidatPersonne.datesignature',
                    'ActioncandidatPersonne.positionfiche',
                    'Actioncandidat.name',
                    'Partenaire.libstruc',
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom',
                    'Referent.qual',
                    'Referent.nom',
                    'Referent.prenom',
                    'Dossier.matricule'
                ),
                'joins' => $joins,
                'contain' => false,
                'order' => array( '"ActioncandidatPersonne"."datesignature" ASC' ),
                'conditions' => $conditions
            );
// debug($conditions);
// debug($criteresfichescandidature);
            return $query;
        }
    }
?>