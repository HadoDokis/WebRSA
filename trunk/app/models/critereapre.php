<?php
    class Critereapre extends AppModel
    {
        var $name = 'Critereapre';
        var $useTable = false;
//         var $actsAs = array( 'Enumerable' );

        var $enumFields = array(
            'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
            'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' )
        );

        function search( $etatApre, $mesCodesInsee, $filtre_zone_geo, $criteresapres, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array();

            if( !empty( $etatApre ) ) {
                if( $etatApre == 'Critereapre::incomplete' ) {
                    $conditions[] = 'Apre.etatdossierapre = \'INC\'';
                }
            }
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
            $datedemandeapre = Set::extract( $criteresapres, 'Filtre.datedemandeapre' );
            $locaadr = Set::extract( $criteresapres, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criteresapres, 'Filtre.numcomptt' );
            $nir = Set::extract( $criteresapres, 'Filtre.nir' );
            $typedemandeapre = Set::extract( $criteresapres, 'Filtre.typedemandeapre' );
            $activitebeneficiaire = Set::extract( $criteresapres, 'Filtre.activitebeneficiaire' );
            $natureaidesapres = Set::extract( $criteresapres, 'Filtre.natureaidesapres' );

            /// Critères sur le CI - date de saisi contrat
            if( isset( $criteresapres['Filtre']['datedemandeapre'] ) && !empty( $criteresapres['Filtre']['datedemandeapre'] ) ) {
                $valid_from = ( valid_int( $criteresapres['Filtre']['datedemandeapre_from']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['day'] ) );
                $valid_to = ( valid_int( $criteresapres['Filtre']['datedemandeapre_to']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Contratinsertion.datedemandeapre BETWEEN \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_from']['year'], $criteresapres['Filtre']['datedemandeapre_from']['month'], $criteresapres['Filtre']['datedemandeapre_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_to']['year'], $criteresapres['Filtre']['datedemandeapre_to']['month'], $criteresapres['Filtre']['datedemandeapre_to']['day'] ) ).'\'';
                }
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresapres['Filtre'][$criterePersonne] ) && !empty( $criteresapres['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \'%'.$criteresapres['Filtre'][$criterePersonne].'%\'';
                }
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            /// Critères sur l'adresse - canton
            if( Configure::read( 'CG.cantons' ) ) {
                if( isset( $criteresapres['Canton']['canton'] ) && !empty( $criteresapres['Canton']['canton'] ) ) {
                    $this->Canton =& ClassRegistry::init( 'Canton' );
                    $conditions[] = $this->Canton->queryConditions( $criteresapres['Canton']['canton'] );
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

            //Type de demande
            if( !empty( $typedemandeapre ) ) {
                $conditions[] = 'Apre.typedemandeapre = \''.Sanitize::clean( $typedemandeapre ).'\'';
            }

            //Activité du bénéficiaire
            if( !empty( $activitebeneficiaire ) ) {
                $conditions[] = 'Apre.activitebeneficiaire = \''.Sanitize::clean( $activitebeneficiaire ).'\'';
            }

            //Nature de l'aide
            if( !empty( $natureaidesapres ) ) {
                $table = Inflector::tableize( $natureaidesapres );
                $conditions[] = "Apre.id IN ( SELECT $table.apre_id FROM $table )";
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Apre"."id"',
                    '"Apre"."personne_id"',
                    '"Apre"."numeroapre"',
                    '"Apre"."typedemandeapre"',
                    '"Apre"."datedemandeapre"',
                    '"Apre"."naturelogement"',
                    '"Apre"."anciennetepoleemploi"',
                    '"Apre"."activitebeneficiaire"',
                    '"Apre"."etatdossierapre"',
                    '"Apre"."dateentreeemploi"',
                    '"Apre"."typecontrat"',
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
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Apre.personne_id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
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
                    )/*,
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
                    ),
                    array(
                        'table'      => 'detailsdroitsrsa',
                        'alias'      => 'Detaildroitrsa',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Detaildroitrsa.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'detailscalculsdroitsrsa',
                        'alias'      => 'Detailcalculdroitrsa',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Detailcalculdroitrsa.detaildroitrsa_id = Detaildroitrsa.id' )
                    )*/
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>