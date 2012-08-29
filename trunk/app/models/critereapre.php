<?php
	class Critereapre extends AppModel
	{
		public $name = 'Critereapre';

		public $useTable = false;

		/**
		*
		*/

		public function search( $etatApre, $mesCodesInsee, $filtre_zone_geo, $criteresapres, $lockedDossiers ) {
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

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

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
			$isdecision = Set::extract( $criteresapres, 'Filtre.isdecision' );
			$decisionapre = Set::extract( $criteresapres, 'Filtre.decisionapre' );
			$dateimpressionapre = Set::extract( $criteresapres, 'Filtre.dateimpressionapre' );
			$dateprint = Set::extract( $criteresapres, 'Filtre.dateprint' );
			$structurereferente_id = Set::extract( $criteresapres, 'Filtre.structurereferente_id' );
			$referent_id = Set::extract( $criteresapres, 'Filtre.referent_id' );
			$themeapre66_id = Set::extract( $criteresapres, 'Filtre.themeapre66_id' );
			$themeapre66_id = Set::extract( $criteresapres, 'Filtre.themeapre66_id' );
			$typeaideapre66_id = Set::extract( $criteresapres, 'Filtre.typeaideapre66_id' );

// debug($criteresapres);
			/// Critères sur la demande APRE - date de demande
			
			$modelCG = 'Apre.datedemandeapre';
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$modelCG = 'Aideapre66.datedemande';
			}
			if( isset( $criteresapres['Filtre']['datedemandeapre'] ) && !empty( $criteresapres['Filtre']['datedemandeapre'] ) ) {
				$valid_from = ( valid_int( $criteresapres['Filtre']['datedemandeapre_from']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['day'] ) );
				$valid_to = ( valid_int( $criteresapres['Filtre']['datedemandeapre_to']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = $modelCG.' BETWEEN \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_from']['year'], $criteresapres['Filtre']['datedemandeapre_from']['month'], $criteresapres['Filtre']['datedemandeapre_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_to']['year'], $criteresapres['Filtre']['datedemandeapre_to']['month'], $criteresapres['Filtre']['datedemandeapre_to']['day'] ) ).'\'';
				}
			}

			/// Critères sur la relance d'APRE - date de relance
			if( isset( $criteresapres['Filtre']['daterelance'] ) && !empty( $criteresapres['Filtre']['daterelance'] ) ) {
				$valid_from = ( valid_int( $criteresapres['Filtre']['daterelance_from']['year'] ) && valid_int( $criteresapres['Filtre']['daterelance_from']['month'] ) && valid_int( $criteresapres['Filtre']['daterelance_from']['day'] ) );
				$valid_to = ( valid_int( $criteresapres['Filtre']['daterelance_to']['year'] ) && valid_int( $criteresapres['Filtre']['daterelance_to']['month'] ) && valid_int( $criteresapres['Filtre']['daterelance_to']['day'] ) );
				if( $valid_from && $valid_to ) {
// 					$conditions[] = 'Relanceapre.daterelance BETWEEN \''.implode( '-', array( $criteresapres['Filtre']['daterelance_from']['year'], $criteresapres['Filtre']['daterelance_from']['month'], $criteresapres['Filtre']['daterelance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Filtre']['daterelance_to']['year'], $criteresapres['Filtre']['daterelance_to']['month'], $criteresapres['Filtre']['daterelance_to']['day'] ) ).'\'';
                    $conditions[] = 'Apre.id IN (
                        SELECT relancesapres.apre_id
                            FROM relancesapres
                            WHERE relancesapres.daterelance BETWEEN \''.implode( '-', array( $criteresapres['Filtre']['daterelance_from']['year'], $criteresapres['Filtre']['daterelance_from']['month'], $criteresapres['Filtre']['daterelance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Filtre']['daterelance_to']['year'], $criteresapres['Filtre']['daterelance_to']['month'], $criteresapres['Filtre']['daterelance_to']['day'] ) ).'\'
                    )';
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
					$this->Canton = ClassRegistry::init( 'Canton' );
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

			// ...
			if( !empty( $matricule ) ) {
				$conditions[] = 'Dossier.matricule ILIKE \''.$this->wildcard( $matricule ).'\'';
			}
			// ...
			if( !empty( $numdemrsa ) ) {
				$conditions[] = 'Dossier.numdemrsa ILIKE \''.$this->wildcard( $numdemrsa ).'\'';
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

			//Décision émise sur le dossier APRE
			if( !empty( $isdecision ) ) {
				$conditions[] = 'Apre.isdecision = \''.Sanitize::clean( $isdecision ).'\'';
			}

			//Accord ou Rejet
			if( !empty( $decisionapre ) ) {
				$conditions[] = 'Aideapre66.decisionapre = \''.Sanitize::clean( $decisionapre ).'\'';
			}

			//Thème de l'aide
			if( !empty( $themeapre66_id ) ) {
				$conditions[] = 'Aideapre66.themeapre66_id = \''.Sanitize::clean( $themeapre66_id ).'\'';
			}

			//Type d'aide
			if( !empty( $typeaideapre66_id ) ) {
				$conditions[] = 'Aideapre66.typeaideapre66_id = \''.Sanitize::clean( suffix( $typeaideapre66_id ) ).'\'';
			}

			//Nature de l'aide
			if( !empty( $natureaidesapres ) ) {
				$table = Inflector::tableize( $natureaidesapres );
				$conditions[] = "Apre.id IN ( SELECT $table.apre_id FROM $table )";
			}


			// Statut impression
			if( !empty( $dateimpressionapre ) && in_array( $dateimpressionapre, array( 'I', 'N' ) ) ) {
				if( $dateimpressionapre == 'I' ) {
					$conditions[] = 'Apre.dateimpressionapre IS NOT NULL';
				}
				else {
					$conditions[] = 'Apre.dateimpressionapre IS NULL';
				}
			}

			// Date d'impression
			if( !empty( $dateprint ) && $dateprint != 0 ) {
				$dateimpressionapre_from = Set::extract( $criteres, 'Filtre.dateimpressionapre_from' );
				$dateimpressionapre_to = Set::extract( $criteres, 'Filtre.dateimpressionapre_to' );
				// FIXME: vérifier le bon formatage des dates
				$dateimpressionapre_from = $dateimpressionapre_from['year'].'-'.$dateimpressionapre_from['month'].'-'.$dateimpressionapre_from['day'];
				$dateimpressionapre_to = $dateimpressionapre_to['year'].'-'.$dateimpressionapre_to['month'].'-'.$dateimpressionapre_to['day'];

				$conditions[] = 'Apre.dateimpressionapre BETWEEN \''.$dateimpressionapre_from.'\' AND \''.$dateimpressionapre_to.'\'';
			}


			//Structure référente où l'apre est faite
			if( !empty( $structurereferente_id ) ) {
				$conditions[] = 'Apre.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
			}


			//Référent de l'APRE
			if( !empty( $referent_id ) ) {
				$conditions[] = 'Apre.referent_id = \''.Sanitize::clean( suffix( $referent_id ) ).'\'';
			}

           // Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
            if( $criteresapres['Dossier']['dernier'] ) {
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
                                    nir_correct13( Personne.nir )
                                    AND nir_correct13( personnes.nir )
                                    AND SUBSTRING( TRIM( BOTH \' \' FROM personnes.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH \' \' FROM Personne.nir ) FROM 1 FOR 13 )
                                    AND personnes.dtnai = Personne.dtnai
                                )
                                OR
                                (
                                    UPPER(personnes.nom) = UPPER(Personne.nom)
                                    AND UPPER(personnes.prenom) = UPPER(Personne.prenom)
                                    AND personnes.dtnai = Personne.dtnai
                                )
                            )
                        ORDER BY dossiers.dtdemrsa DESC
                        LIMIT 1
                )';
            }



			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );

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
					'"Apre"."isdecision"',
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
					'"Aideapre66"."decisionapre"',
					'"Aideapre66"."datedemande"',
					'"Apre"."isdecision"',
					'"Referent"."nom"',
					'"Referent"."prenom"',
					'Structurereferente.lib_struc'
// 					'"Relanceapre"."daterelance"',
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
// 					array(
// 						'table'      => 'relancesapres',
// 						'alias'      => 'Relanceapre',
// 						'type'       => 'LEFT OUTER',
// 						'foreignKey' => false,
// 						'conditions' => array( 'Relanceapre.apre_id = Apre.id' )
// 					),
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
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'aidesapres66',
						'alias'      => 'Aideapre66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
								"Aideapre66.apre_id = Apre.id"
						)
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.id = Apre.structurereferente_id' )
					),
					array(
						'table'      => 'referents',
						'alias'      => 'Referent',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Referent.id = Apre.referent_id' )
					),/*,
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id' )
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
				$this->Apre = ClassRegistry::init( 'Apre' );
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