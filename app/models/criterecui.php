<?php
    class Criterecui extends AppModel
    {
        var $name = 'Criterecui';
        var $useTable = false;

        function search( $mesCodesInsee, $filtre_zone_geo, $criterescuis, $lockedDossiers ) {
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
            $datecontrat = Set::extract( $criterescuis, 'Search.Cui.datecontrat' );
            $secteur = Set::extract( $criterescuis, 'Search.Cui.secteur' );

            /// Critères sur le CI - date de saisi contrat
            if( isset( $criterescuis['Filtre']['datecontrat'] ) && !empty( $criterescuis['Filtre']['datecontrat'] ) ) {
                $valid_from = ( valid_int( $criterescuis['Filtre']['datecontrat_from']['year'] ) && valid_int( $criterescuis['Filtre']['datecontrat_from']['month'] ) && valid_int( $criterescuis['Filtre']['datecontrat_from']['day'] ) );
                $valid_to = ( valid_int( $criterescuis['Filtre']['datecontrat_to']['year'] ) && valid_int( $criterescuis['Filtre']['datecontrat_to']['month'] ) && valid_int( $criterescuis['Filtre']['datecontrat_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Cui.datecontrat BETWEEN \''.implode( '-', array( $criterescuis['Filtre']['datecontrat_from']['year'], $criterescuis['Filtre']['datecontrat_from']['month'], $criterescuis['Filtre']['datecontrat_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescuis['Filtre']['datecontrat_to']['year'], $criterescuis['Filtre']['datecontrat_to']['month'], $criterescuis['Filtre']['datecontrat_to']['day'] ) ).'\'';
                }
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criterescuis['Filtre'][$criterePersonne] ) && !empty( $criterescuis['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criterescuis['Filtre'][$criterePersonne] ).'\'';
                }
            }


            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // ...
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
            }

            /// Critères sur l'adresse - canton
            if( Configure::read( 'CG.cantons' ) ) {
                if( isset( $criterescuis['Canton']['canton'] ) && !empty( $criterescuis['Canton']['canton'] ) ) {
                    $this->Canton =& ClassRegistry::init( 'Canton' );
                    $conditions[] = $this->Canton->queryConditions( $criterescuis['Canton']['canton'] );
                }
            }

            // NIR
            if( !empty( $nir ) ) {
                $conditions[] = 'Personne.nir ILIKE \'%'.Sanitize::clean( $nir ).'%\'';
            }

            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            // Personne chargée du suiv
            if( !empty( $secteur ) ) {
                $conditions[] = 'Cui.secteur = \''.Sanitize::clean( $secteur ).'\'';
            }


            /// Référent
//             debug($referent_id);
            if( !empty( $referent_id ) ) {
                $conditions[] = 'PersonneReferent.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
            }


            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Cui"."id"',
                    '"Cui"."personne_id"',
                    '"Cui"."secteur"',
                    '"Cui"."datecontrat"',
                    '"Cui"."nomemployeur"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Dossier"."matricule"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"',
                    '"Adresse"."numcomptt"',
                    '"PersonneReferent"."referent_id"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Cui.personne_id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
                        )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
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
                        'table'      => 'personnes_referents',
                        'alias'      => 'PersonneReferent',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'PersonneReferent.personne_id = Personne.id',
                            'PersonneReferent.dfdesignation IS NULL'
                        )
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>