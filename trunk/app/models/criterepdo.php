<?php
    class Criterepdo extends AppModel
    {
        var $name = 'Criterepdo';
        var $useTable = false;

        function search( $mesCodesInsee, $filtre_zone_geo, $criterespdos, $lockedDossiers ) {
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

            $decisionpdo = Set::extract( $criterespdos, 'Search.Propopdo.decisionpdo_id' );
            $motifpdo = Set::extract( $criterespdos, 'Search.Propopdo.motifpdo' );
            $originepdo = Set::extract( $criterespdos, 'Search.Propopdo.originepdo_id' );
            $nir = Set::extract( $criterespdos, 'Search.Propopdo.nir' );
            $nom = Set::extract( $criterespdos, 'Search.Personne.nom' );
            $prenom = Set::extract( $criterespdos, 'Search.Personne.prenom' );
            $matricule = Set::extract( $criterespdos, 'Search.Dossier.matricule' );
            $numdemrsa = Set::extract( $criterespdos, 'Search.Dossier.numdemrsa' );


            /// Critères sur les PDOs - date de decisonde la PDO
            if( isset( $criterespdos['Propopdo']['datedecisionpdo'] ) && !empty( $criterespdos['Propopdo']['datedecisionpdo'] ) ) {
                $valid_from = ( valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['day'] ) );
                $valid_to = ( valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Propopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdos['Propopdo']['datedecisionpdo_from']['year'], $criterespdos['Propopdo']['datedecisionpdo_from']['month'], $criterespdos['Propopdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Propopdo']['datedecisionpdo_to']['year'], $criterespdos['Propopdo']['datedecisionpdo_to']['month'], $criterespdos['Propopdo']['datedecisionpdo_to']['day'] ) ).'\'';
                }
            }

            /// Critères sur les PDOs - date de reception de la PDO
            if( isset( $criterespdos['Propopdo']['datereceptionpdo'] ) && !empty( $criterespdos['Propopdo']['datereceptionpdo'] ) ) {
                $valid_from = ( valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['year'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['month'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['day'] ) );
                $valid_to = ( valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['year'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['month'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Propopdo.datereceptionpdo BETWEEN \''.implode( '-', array( $criterespdos['Propopdo']['datereceptionpdo_from']['year'], $criterespdos['Propopdo']['datereceptionpdo_from']['month'], $criterespdos['Propopdo']['datereceptionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Propopdo']['datereceptionpdo_to']['year'], $criterespdos['Propopdo']['datereceptionpdo_to']['month'], $criterespdos['Propopdo']['datereceptionpdo_to']['day'] ) ).'\'';
                }
            }
            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            if( !empty( $nom ) ) {
                $conditions[] = 'Personne.nom ILIKE \''.$this->wildcard( $nom ).'\'';
            }
            if( !empty( $prenom ) ) {
                $conditions[] = 'Personne.prenom ILIKE \''.$this->wildcard( $prenom ).'\'';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // ...
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
            }
            // ...
            if( !empty( $numdemrsa ) ) {
                $conditions[] = 'Dossier.numdemrsa = \''.Sanitize::clean( $numdemrsa ).'\'';
            }

            /// Critères sur l'adresse - canton
            if( Configure::read( 'CG.cantons' ) ) {
                if( isset( $criterespdos['Canton']['canton'] ) && !empty( $criterespdos['Canton']['canton'] ) ) {
                    $this->Canton =& ClassRegistry::init( 'Canton' );
                    $conditions[] = $this->Canton->queryConditions( $criterespdos['Canton']['canton'] );
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

            // Décision de la PDO
            if( !empty( $decisionpdo ) ) {
                $conditions[] = 'Propopdo.decisionpdo_id = \''.Sanitize::clean( $decisionpdo ).'\'';
            }


            // Motif de la PDO
            if( !empty( $motifpdo ) ) {
                $conditions[] = 'Propopdo.motifpdo = \''.Sanitize::clean( $motifpdo ).'\'';
            }

            // Origine de la PDO
            if( !empty( $originepdo ) ) {
                $conditions[] = 'Propopdo.originepdo_id = \''.Sanitize::clean( $originepdo ).'\'';
            }




            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Propopdo"."id"',
                    '"Propopdo"."decisionpdo_id"',
                    '"Propopdo"."datereceptionpdo"',
                    '"Propopdo"."datedecisionpdo"',
                    '"Propopdo"."dossier_rsa_id"',
                    '"Propopdo"."motifpdo"',
                    '"Propopdo"."originepdo_id"',
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
                    '"Adresse"."numcomptt"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Dossier.id = Propopdo.dossier_rsa_id' )
                    ),

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
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
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
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>