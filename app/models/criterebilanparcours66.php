<?php
    /**
    * Séance d'équipe pluridisciplinaire.
    *
    * PHP versions 5
    *
    * @package       app
    * @subpackage    app.app.models
    */

    class Criterebilanparcours66 extends AppModel
    {
        var $name = 'Criterebilanparcours66';
        var $useTable = false;


        public function search( $criteresbilansparcours66 ) {
            /// Conditions de base

            $conditions = array();


            foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
                if( isset( $criteresbilansparcours66['Personne'][$criterePersonne] ) && !empty( $criteresbilansparcours66['Personne'][$criterePersonne] ) ) {
                    $conditions[] = 'UPPER(Personne.'.$criterePersonne.') LIKE \''.$this->wildcard( strtoupper( replace_accents( $criteresbilansparcours66['Personne'][$criterePersonne] ) ) ).'\'';
                }
            }

            foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
                if( isset( $criteresbilansparcours66['Dossier'][$critereDossier] ) && !empty( $criteresbilansparcours66['Dossier'][$critereDossier] ) ) {
                    $conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$this->wildcard( $criteresbilansparcours66['Dossier'][$critereDossier] ).'\'';
                }
            }


            if ( isset($criteresbilansparcours66['Bilanparcours66']['choixparcours']) && !empty($criteresbilansparcours66['Bilanparcours66']['choixparcours']) ) {
                $conditions[] = array('Bilanparcours66.choixparcours'=>$criteresbilansparcours66['Bilanparcours66']['choixparcours']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['proposition']) && !empty($criteresbilansparcours66['Bilanparcours66']['proposition']) ) {
                $conditions[] = array('Bilanparcours66.proposition'=>$criteresbilansparcours66['Bilanparcours66']['proposition']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['examenaudition']) && !empty($criteresbilansparcours66['Bilanparcours66']['examenaudition']) ) {
                $conditions[] = array('Bilanparcours66.examenaudition'=>$criteresbilansparcours66['Bilanparcours66']['examenaudition']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['maintienorientation']) && is_numeric($criteresbilansparcours66['Bilanparcours66']['maintienorientation']) ) {
                $conditions[] = array('Bilanparcours66.maintienorientation'=>$criteresbilansparcours66['Bilanparcours66']['maintienorientation']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['referent_id']) && !empty($criteresbilansparcours66['Bilanparcours66']['referent_id']) ) {
                $conditions[] = array('Bilanparcours66.referent_id'=>$criteresbilansparcours66['Bilanparcours66']['referent_id']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['positionbilan']) && !empty($criteresbilansparcours66['Bilanparcours66']['positionbilan']) ) {
                $conditions[] = array('Bilanparcours66.positionbilan'=>$criteresbilansparcours66['Bilanparcours66']['positionbilan']);
            }

            /// Critères sur le Bilan - date du bilan
            if( isset( $criteresbilansparcours66['Bilanparcours66']['datebilan'] ) && !empty( $criteresbilansparcours66['Bilanparcours66']['datebilan'] ) ) {
                $valid_from = ( valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['year'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['month'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['day'] ) );
                $valid_to = ( valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['year'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['month'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Bilanparcours66.datebilan BETWEEN \''.implode( '-', array( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['year'], $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['month'], $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['year'], $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['month'], $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['day'] ) ).'\'';
                }
            }


            // Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
            if( $criteresbilansparcours66['Dossier']['dernier'] ) {
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
                    'table'      => 'orientsstructs',
                    'alias'      => 'Orientstruct',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Orientstruct.id = Bilanparcours66.orientstruct_id' ),
                ),
                array(
                    'table'      => 'personnes',
                    'alias'      => 'Personne',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Personne.id = Orientstruct.personne_id' ),
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
                    'conditions' => array( 'Referent.id = Bilanparcours66.referent_id' ),
                ),
                array(
                    'table'      => 'structuresreferentes',
                    'alias'      => 'Structurereferente',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Structurereferente.id = Referent.structurereferente_id' ),
                ),
                array(
                    'table'      => 'defautsinsertionseps66',
                    'alias'      => 'Defautinsertionep66',
                    'type'       => 'LEFT OUTER',
                    'foreignKey' => false,
                    'conditions' => array( 'Bilanparcours66.id = Defautinsertionep66.bilanparcours66_id' ),
                ),
                array(
                    'table'      => 'saisinesbilansparcourseps66',
                    'alias'      => 'Saisinebilanparcoursep66',
                    'type'       => 'LEFT OUTER',
                    'foreignKey' => false,
                    'conditions' => array( 'Bilanparcours66.id = Saisinebilanparcoursep66.bilanparcours66_id' ),
                ),
                array(
                    'table'      => 'dossierseps',
                    'alias'      => 'Dossierep',
                    'type'       => 'LEFT OUTER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'OR' => array(
                            'Defautinsertionep66.dossierep_id = Dossierep.id',
                            'Saisinebilanparcoursep66.dossierep_id = Dossierep.id',
                        )
                    ),
                )
            );


            $query = array(
                'fields' => array(
                    'Bilanparcours66.id',
                    'Bilanparcours66.orientstruct_id',
                    'Bilanparcours66.referent_id',
                    'Bilanparcours66.datebilan',
                    'Bilanparcours66.choixparcours',
                    'Bilanparcours66.proposition',
                    'Bilanparcours66.examenaudition',
                    'Bilanparcours66.maintienorientation',
                    'Bilanparcours66.saisineepparcours',
                    'Bilanparcours66.positionbilan',
                    'Personne.id',
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom',
                    'Referent.qual',
                    'Referent.nom',
                    'Referent.prenom',
                    'Structurereferente.lib_struc',
                    'Dossier.matricule',
                    'Dossierep.themeep'
                ),
                'joins' => $joins,
                'order' => array( '"Bilanparcours66"."datebilan" ASC' ),
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>