<?php
    class Critereapre extends AppModel
    {
        var $name = 'Critereapre';
        var $useTable = false;

        function search( $etatApre, $mesCodesInsee, $filtre_zone_geo, $criteresapres, $lockedDossiers ) {

            /// Conditions de base
            $conditions = array(
            );

            /*if( !empty( $etatApre ) ) {
                if( $etatApre == 'Critereapre::all' ) {
					$conditions[] = 'Apre.statutapre = \'C\'';
                }
                else if( $etatApre == 'Critereapre::forfaitaire'  ) {
                    $conditions[] = 'Apre.statutapre = \'F\'';
                }
            }*/

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
            $daterelance = Set::extract( $criteresapres, 'Filtre.daterelance' );
            $locaadr = Set::extract( $criteresapres, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criteresapres, 'Filtre.numcomptt' );
            $numdemrsa = Set::extract( $criteresapres, 'Filtre.numdemrsa' );
            $matricule = Set::extract( $criteresapres, 'Filtre.matricule' );
            $nir = Set::extract( $criteresapres, 'Filtre.nir' );
            $typedemandeapre = Set::extract( $criteresapres, 'Filtre.typedemandeapre' );
            $etatdossierapre = Set::extract( $criteresapres, 'Filtre.etatdossierapre' );
            $eligibiliteapre = Set::extract( $criteresapres, 'Filtre.eligibiliteapre' );
            $activitebeneficiaire = Set::extract( $criteresapres, 'Filtre.activitebeneficiaire' );
            $natureaidesapres = Set::extract( $criteresapres, 'Filtre.natureaidesapres' );
            $statutapre = Set::extract( $criteresapres, 'Filtre.statutapre' );
            $tiers = Set::extract( $criteresapres, 'Filtre.tiersprestataire' );

            /// Critères sur la demande APRE - date de demande
            if( isset( $criteresapres['Filtre']['datedemandeapre'] ) && !empty( $criteresapres['Filtre']['datedemandeapre'] ) ) {
                $valid_from = ( valid_int( $criteresapres['Filtre']['datedemandeapre_from']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['day'] ) );
                $valid_to = ( valid_int( $criteresapres['Filtre']['datedemandeapre_to']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Apre.datedemandeapre BETWEEN \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_from']['year'], $criteresapres['Filtre']['datedemandeapre_from']['month'], $criteresapres['Filtre']['datedemandeapre_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_to']['year'], $criteresapres['Filtre']['datedemandeapre_to']['month'], $criteresapres['Filtre']['datedemandeapre_to']['day'] ) ).'\'';
                }
            }

            /// Critères sur la relance d'APRE - date de relance
            if( isset( $criteresapres['Filtre']['daterelance'] ) && !empty( $criteresapres['Filtre']['daterelance'] ) ) {
                $valid_from = ( valid_int( $criteresapres['Filtre']['daterelance_from']['year'] ) && valid_int( $criteresapres['Filtre']['daterelance_from']['month'] ) && valid_int( $criteresapres['Filtre']['daterelance_from']['day'] ) );
                $valid_to = ( valid_int( $criteresapres['Filtre']['daterelance_to']['year'] ) && valid_int( $criteresapres['Filtre']['daterelance_to']['month'] ) && valid_int( $criteresapres['Filtre']['daterelance_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Relanceapre.daterelance BETWEEN \''.implode( '-', array( $criteresapres['Filtre']['daterelance_from']['year'], $criteresapres['Filtre']['daterelance_from']['month'], $criteresapres['Filtre']['daterelance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Filtre']['daterelance_to']['year'], $criteresapres['Filtre']['daterelance_to']['month'], $criteresapres['Filtre']['daterelance_to']['day'] ) ).'\'';
                }
            }


            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresapres['Filtre'][$criterePersonne] ) && !empty( $criteresapres['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresapres['Filtre'][$criterePersonne] ).'\'';
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

            // N° Dossier RSA
            if( !empty( $numdemrsa ) ) {
                $conditions[] = 'Dossier.numdemrsa ILIKE \'%'.Sanitize::clean( $numdemrsa ).'%\'';
            }

            // N° CAF
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule ILIKE \'%'.Sanitize::clean( $matricule ).'%\'';
            }

            //Type de demande
            if( !empty( $typedemandeapre ) ) {
                $conditions[] = 'Apre.typedemandeapre = \''.Sanitize::clean( $typedemandeapre ).'\'';
            }

            //Activité du bénéficiaire
            if( !empty( $activitebeneficiaire ) ) {
                $conditions[] = 'Apre.activitebeneficiaire = \''.Sanitize::clean( $activitebeneficiaire ).'\'';
            }

            //Etat du dossier apre
            if( !empty( $etatdossierapre ) ) {
                $conditions[] = 'Apre.etatdossierapre = \''.Sanitize::clean( $etatdossierapre ).'\'';
            }

            //Eligibilité du dossier apre
            if( !empty( $eligibiliteapre ) ) {
                $conditions[] = 'Apre.eligibiliteapre = \''.Sanitize::clean( $eligibiliteapre ).'\'';
            }

            //Eligibilité du dossier apre
            if( !empty( $statutapre ) ) {
                $conditions[] = 'Apre.statutapre = \''.Sanitize::clean( $statutapre ).'\'';
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
                    '"Apre"."eligibiliteapre"',
                    '"Apre"."typecontrat"',
                    '"Apre"."statutapre"',
                    '"Apre"."mtforfait"',
                    '"Apre"."nbenf12"',
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
                    '"Adressefoyer"."rgadr"',
                    '"Adresse"."numcomptt"',
                    '"Relanceapre"."id"',
                    '"Relanceapre"."daterelance"',
//                     '"ApreComiteapre"."comiteapre_id"',
//                     '"ApreComiteapre"."apre_id"',
//                     '"ApreComiteapre"."decisioncomite"',
//                     '"Comiteapre"."datecomite"',
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
//                     array(
//                         'table'      => 'apres_comitesapres',
//                         'alias'      => 'ApreComiteapre',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'ApreComiteapre.apre_id = Apre.id' )
//                     ),
//                     array(
//                         'table'      => 'comitesapres',
//                         'alias'      => 'Comiteapre',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array(
//                             'ApreComiteapre.comiteapre_id = Comiteapre.id'
//                         )
//                     ),
                    array(
                        'table'      => 'relancesapres',
                        'alias'      => 'Relanceapre',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Relanceapre.apre_id = Apre.id' )
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
                        'conditions' => array(
                            'Foyer.id = Adressefoyer.foyer_id',
                            'Adressefoyer.rgadr = \'01\'',
                             'Adressefoyer.id IN '.ClassRegistry::init( 'Adressefoyer' )->sqlFoyerActuelUnique()
                        )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
                    /*array(
                        'table'      => 'tiersprestatairesapres',
                        'alias'      => 'Tiersprestataireapre',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array(
                             "Tiersprestataireapre.id = {$model}.tiersprestataireapre_id"
                        )
                    )*//*,
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
                'conditions' => $conditions,
            );

            ///Tiers prestataire lié à l'apre
            /// FIXME: à la mode CakePHP ?
            if( !empty( $tiers ) ) {
                $subQueries = array();
                $this->Apre =& ClassRegistry::init( 'Apre' );
                foreach( $this->Apre->modelsFormation as $model ) {
                    $table = Inflector::tableize( $model );

                    $query['joins'][] = array(
                        'table'      => $table,
                        'alias'      => $model,
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( "{$model}.apre_id = Apre.id" )
                    );

                    $subQueries[] = "( SELECT COUNT(tiersprestatairesapres.id) FROM tiersprestatairesapres WHERE tiersprestatairesapres.aidesliees = '$model' AND tiersprestatairesapres.id = $tiers AND $model.tiersprestataireapre_id = tiersprestatairesapres.id ) > 0";
                }

                $query['conditions'][] = array( 'or' => $subQueries );
            }

            return $query;
        }
    }
?>