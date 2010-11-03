<?php
    App::import( 'Sanitize' );

	class Cohorteci extends AppModel
	{
		public $name = 'Cohorteci';

		public $useTable = false;

		/**
		*
		*/

		public function search( $statutValidation, $mesCodesInsee, $filtre_zone_geo, $criteresci, $lockedDossiers ) {
			/// Conditions de base
			$conditions = array(/* '1 = 1' */);


			if( !empty( $statutValidation ) ) {
				if( $statutValidation == 'Decisionci::nonvalide' ) {
					$conditions[] = '( ( Contratinsertion.decision_ci <> \'V\' ) AND /*( Contratinsertion.decision_ci <> \'E\' ) ) OR ( */Contratinsertion.decision_ci IS NOT NULL )'; ///FIXME: pourquoi avoir mis <>E !!!
				}
				else if( $statutValidation == 'Decisionci::enattente' ) {
					$conditions[] = 'Contratinsertion.decision_ci = \'E\'';
				}
				else if( $statutValidation == 'Decisionci::valides' ) {
					$conditions[] = 'Contratinsertion.decision_ci IS NOT NULL';
					$conditions[] = 'Contratinsertion.decision_ci = \'V\'';
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
			$date_saisi_ci = Set::extract( $criteresci, 'Filtre.date_saisi_ci' );
			$decision_ci = Set::extract( $criteresci, 'Filtre.decision_ci' );
			$datevalidation_ci = Set::extract( $criteresci, 'Filtre.datevalidation_ci' );
			$df_ci = Set::extract( $criteresci, 'Filtre.df_ci' );
			$locaadr = Set::extract( $criteresci, 'Filtre.locaadr' );
			$numcomptt = Set::extract( $criteresci, 'Filtre.numcomptt' );
			$nir = Set::extract( $criteresci, 'Filtre.nir' );
			$natpf = Set::extract( $criteresci, 'Filtre.natpf' );
			$personne_suivi = Set::extract( $criteresci, 'Filtre.pers_charg_suivi' );
			$forme_ci = Set::extract( $criteresci, 'Filtre.forme_ci' );
			$structurereferente_id = Set::extract( $criteresci, 'Filtre.structurereferente_id' );
			$referent_id = Set::extract( $criteresci, 'Filtre.referent_id' );
			$matricule = Set::extract( $criteresci, 'Filtre.matricule' );


			/// Critères sur le CI - date de saisi contrat
			if( isset( $criteresci['Filtre']['date_saisi_ci'] ) && !empty( $criteresci['Filtre']['date_saisi_ci'] ) ) {
				$valid_from = ( valid_int( $criteresci['Filtre']['date_saisi_ci_from']['year'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_from']['month'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_from']['day'] ) );
				$valid_to = ( valid_int( $criteresci['Filtre']['date_saisi_ci_to']['year'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_to']['month'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Contratinsertion.date_saisi_ci BETWEEN \''.implode( '-', array( $criteresci['Filtre']['date_saisi_ci_from']['year'], $criteresci['Filtre']['date_saisi_ci_from']['month'], $criteresci['Filtre']['date_saisi_ci_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresci['Filtre']['date_saisi_ci_to']['year'], $criteresci['Filtre']['date_saisi_ci_to']['month'], $criteresci['Filtre']['date_saisi_ci_to']['day'] ) ).'\'';
				}
			}

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPersonne = array();
			foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
				if( isset( $criteresci['Filtre'][$criterePersonne] ) && !empty( $criteresci['Filtre'][$criterePersonne] ) ) {
					$conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresci['Filtre'][$criterePersonne] ).'\'';
				}
			}

			// ...
			if( !empty( $decision_ci ) ) {
				$conditions[] = 'Contratinsertion.decision_ci = \''.Sanitize::clean( $decision_ci ).'\'';
			}

			// ...
			if( !empty( $datevalidation_ci ) && dateComplete( $criteresci, 'Filtre.datevalidation_ci' ) ) {
				$datevalidation_ci = $datevalidation_ci['year'].'-'.$datevalidation_ci['month'].'-'.$datevalidation_ci['day'];
				$conditions[] = 'Contratinsertion.datevalidation_ci = \''.$datevalidation_ci.'\'';
			}

			// ...
			if( !empty( $df_ci ) && dateComplete( $criteresci, 'Filtre.df_ci' ) ) {
				$df_ci = $df_ci['year'].'-'.$df_ci['month'].'-'.$df_ci['day'];
				$conditions[] = 'Contratinsertion.df_ci = \''.$df_ci.'\'';
			}

			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}

			// ...
			if( !empty( $matricule ) ) {
				$conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
			}

			// Nature de la prestation
			if( !empty( $natpf ) ) {
				//$conditions[] = 'detailscalculsdroitsrsa.natpf ILIKE \'%'.Sanitize::clean( $natpf ).'%\'';
				$conditions[] = 'Detaildroitrsa.id IN (
									SELECT detailscalculsdroitsrsa.detaildroitrsa_id
										FROM detailscalculsdroitsrsa
										WHERE detailscalculsdroitsrsa.natpf ILIKE \'%'.Sanitize::clean( $natpf ).'%\'
								)';
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteresci['Canton']['canton'] ) && !empty( $criteresci['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criteresci['Canton']['canton'] );
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
			if( !empty( $personne_suivi ) ) {
				$conditions[] = 'Contratinsertion.pers_charg_suivi = \''.Sanitize::clean( $personne_suivi ).'\'';
			}

			// Forme du contrat
			if( !empty( $forme_ci ) ) {
				$conditions[] = 'Contratinsertion.forme_ci = \''.Sanitize::clean( $forme_ci ).'\'';
			}

			/// Structure référente
			if( !empty( $structurereferente_id ) ) {
				$conditions[] = 'Contratinsertion.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
			}

			/// Référent
//             debug($referent_id);
			if( !empty( $referent_id ) ) {
				$conditions[] = 'PersonneReferent.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
			}

			/**
			SELECT DISTINCT contratsinsertion.id
				FROM contratsinsertion
					INNER JOIN personnes ON ( personnes.id = contratsinsertion.personne_id )
					INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = 'RSA' AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' ) )
					INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
					INNER JOIN adressesfoyers ON ( adressesfoyers.foyer_id = foyers.id AND adressesfoyers.rgadr = '01' )
					INNER JOIN adresses ON ( adressesfoyers.adresse_id = adresses.id)
				WHERE
					contratsinsertion.date_saisi_ci = '2009-01-01'
					AND contratsinsertion.decision_ci = 'E'
					AND contratsinsertion.datevalidation_ci = '2009-01-01'
					AND adresses.locaadr ILIKE '%denis%'
			*/

			/// Requête
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );
			$this->Dossier = ClassRegistry::init( 'Dossier' );

			$query = array(
				'fields' => array(
					'"Contratinsertion"."id"',
					'"Contratinsertion"."personne_id"',
					'"Contratinsertion"."num_contrat"',
					'"Contratinsertion"."referent_id"',
					'"Contratinsertion"."structurereferente_id"',
					'"Contratinsertion"."rg_ci"',
					'"Contratinsertion"."decision_ci"',
					'"Contratinsertion"."dd_ci"',
					'"Contratinsertion"."df_ci"',
					'"Contratinsertion"."datevalidation_ci"',
					'"Contratinsertion"."duree_engag"',
					'"Contratinsertion"."date_saisi_ci"',
					'"Contratinsertion"."pers_charg_suivi"',
					'"Contratinsertion"."observ_ci"',
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
						'conditions' => array( 'Personne.id = Contratinsertion.personne_id' )
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
						'table'      => 'personnes_referents',
						'alias'      => 'PersonneReferent',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'PersonneReferent.personne_id = Personne.id',
							'PersonneReferent.dfdesignation IS NULL'
						)
					),
				),
				'limit' => 10,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>
