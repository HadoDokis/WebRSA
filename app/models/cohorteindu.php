<?php
    App::import( 'Sanitize' );

    class Cohorteindu extends AppModel
    {
        var $name = 'Cohorteindu';
        var $useTable = false;

        var $validate = array(
            'mtmoucompta' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer un montant valide',
                'allowEmpty' => true
            ),
            'compare' => array(
                'rule' => 'mountComparator',
                'message' => 'Ce champ ne peut rester vide, si vous avez saisi un montant'
            )
        );

        function mountComparator($data) {
            $compare = Set::extract( $this->data, 'Cohorteindu.compare' );
            $mtmoucompta = Set::extract( $this->data, 'Cohorteindu.mtmoucompta' );

            return ( ( empty( $compare ) && empty( $mtmoucompta ) ) || ( !empty( $compare ) && !empty( $mtmoucompta ) ) );
        }


        function search( $mesCodesInsee, $filtre_zone_geo, $criteresindu, $lockedDossiers ) {
// debug( $criteresindu );
            /// Conditions de base
            $conditions = array(/* '1 = 1' */);

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $natpfcre = Set::extract( $criteresindu, 'Cohorteindu.natpfcre' );
            $locaadr = Set::extract( $criteresindu, 'Cohorteindu.locaadr' );
            $nom = Set::extract( $criteresindu, 'Cohorteindu.nom' );
            $typeparte = Set::extract( $criteresindu, 'Cohorteindu.typeparte' );
            $structurereferente_id = Set::extract( $criteresindu, 'Cohorteindu.structurereferente_id' );
            $mtmoucompta = Set::extract( $criteresindu, 'Cohorteindu.mtmoucompta' );
            $compare = Set::extract( $criteresindu, 'Cohorteindu.compare' );
// debug( $compare );

            // Type d'indu
//             if( !empty( $natpfcre ) ) {
//                 $conditions[] = 'Infofinanciere.natpfcre = \''.Sanitize::clean( $natpfcre ).'\'';
//             }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // Nom allocataire
            if( !empty( $nom ) ) {
                $conditions[] = 'Personne.nom ILIKE \'%'.Sanitize::clean( $nom ).'%\'';
            }

            // Suivi
            if( !empty( $typeparte ) ) {
                $conditions[] = 'Dossier.typeparte = \''.Sanitize::clean( $typeparte ).'\'';
            }

            // Structure référente
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Structurereferente.id = \''.$structurereferente_id.'\'';
            }


            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            // FIXME -> qu'a-t'on dans la base à un instant t ?
            $date_start = date( 'Y-m-d', strtotime( 'previous month', strtotime( date( 'Y-m-01' ) ) ) );
            $date_end = date( 'Y-m-d', strtotime( 'next month', strtotime( date( 'Y-m-d', strtotime( $date_start ) ) ) ) - 1 );

            $query = array(
                'fields' => array(
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."matricule"',
                    '"Dossier"."typeparte"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"',
                    '"Situationdossierrsa"."id"',
                    '"Situationdossierrsa"."etatdosrsa"',
                    '"Situationdossierrsa"."etatdosrsa"',
                    '\''.$date_start.'\' AS "moismoucompta"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            '( Prestation.rolepers = \'DEM\' )',
                        )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \'2\', \'3\', \'4\' ) )' )
                    ),
                ),
                'limit' => 10,
                'conditions' => array()
            );

            $typesAllocation = array( 'AllocationComptabilisee', 'IndusConstates', 'IndusTransferesCG', 'RemisesIndus', 'AnnulationsFaibleMontant', 'AutresAnnulations' );
            $conditionsNotNull = array();
            $conditionsComparator = array();
            $conditionsNat = array();

            foreach( $typesAllocation as $type ) {
                $meu  = Inflector::singularize( Inflector::tableize( $type ) );
                $query['fields'][] = '"'.$type.'"."mtmoucompta" AS mt_'.$meu;

                $join = array(
                    'table'      => 'infosfinancieres',
                    'alias'      => $type,
                    'type'       => 'LEFT OUTER',
                    'foreignKey' => false,
                    'conditions' => array(
                        $type.'.dossier_rsa_id = Dossier.id',
                        $type.'.type_allocation' => $type,
                        '"'.$type.'"."moismoucompta" BETWEEN \''.$date_start.'\' AND \''.$date_end.'\'',
                    )
                );

                $query['joins'][] = $join;
                $conditionsNotNull[] = $type.'.mtmoucompta IS NOT NULL';

                // Montant indu + comparatif vis à vis du montant
                if( !empty( $compare ) && !empty( $mtmoucompta ) ) {
                    $conditionsComparator[] = $type.'.mtmoucompta '.$compare.' '.Sanitize::clean( $mtmoucompta );
                }

                // Nature de la prestation de créance
                if( !empty( $natpfcre ) ) {
                    $conditionsNat[] = $type.'.natpfcre = \''.Sanitize::clean( $natpfcre ).'\'';
                }
            }
            $conditions[] = '( '.implode( ' OR ', $conditionsNotNull  ).' )';
            if( !empty( $conditionsComparator ) ) {
                $conditions[] = '( '.implode( ' OR ', $conditionsComparator  ).' )';
            }
            if( !empty( $natpfcre ) ) {
                $conditions[] = '( '.implode( ' OR ', $conditionsNat  ).' )';
            }
            $query['conditions'] = Set::merge( $query['conditions'], $conditions );

            return $query;
        }
    }
?>