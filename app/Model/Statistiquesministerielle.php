<?php
	App::import( 'Sanitize' );

	class Statistiquesministerielle extends AppModel
	{
		public $name = 'Statistiqueministerielle';
		public $useTable = false;

		public $isEmploi = ' SELECT t.id FROM typesorients AS t WHERE t.lib_type_orient LIKE \'Emploi%\' ';
 		public $isSocial = ' SELECT t.id FROM typesorients AS t WHERE (t.lib_type_orient NOT IN ( SELECT t2.lib_type_orient FROM typesorients AS t2 WHERE t2.lib_type_orient LIKE \'Emploi%\'  ) ) ';

		public $now_and_before = "
			INNER JOIN ( 
				SELECT personne_id, typeorient_id, rgorient, row_number() OVER (partition BY personne_id ORDER BY date_valid DESC, rgorient DESC) rank 
				FROM orientsstructs 
			) noworient ON noworient.personne_id = p.id AND noworient.rank = 1 
		 	INNER JOIN ( 
		 		SELECT personne_id, typeorient_id, rgorient, row_number() OVER (partition BY personne_id ORDER BY date_valid DESC, rgorient DESC) rank 
		 		FROM orientsstructs 
		 	) beforeorient ON beforeorient.personne_id = p.id AND beforeorient.rank = 2
		";

		//##############################################################################
		//
		// INDICATEURS ORIENTATIONS :
		//
		//##############################################################################

		/**
		 * Calcul des indicateurs d'orientation.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function indicateursOrientations($args) { 
			set_time_limit(0);
			$resultats = array();
			$resultats['age'] = $this->_indicOrientAge($args);
			$resultats['situation'] = $this->_indicOrientSituation($args);
			$resultats['formation'] = $this->_indicOrientFormation($args);
			$resultats['anciennete'] = $this->_indicOrientAnciennete($args);
			return $resultats;
		}

		/**
		 * Calcul des indicateurs d'orientation pour le bloc 'age'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function _indicOrientAge($args) {
			$globalQuery = "
				SELECT
					(
						CASE
							WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 0 AND 24 THEN '0 - 24'
							WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 25 AND 29 THEN '25 - 29'
							WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 30 AND 39 THEN '30 - 39'
							WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 40 AND 49 THEN '40 - 49'
							WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 50 AND 59 THEN '50 - 59'
							WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) >= 60 THEN '>= 60'
							ELSE 'NC'
						END
					) AS age_range,
					COUNT(DISTINCT(personnes.id)) AS count
				FROM personnes
					INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
					INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
					INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
					LEFT OUTER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
				WHERE
					calculsdroitsrsa.toppersdrodevorsa = '1'
					AND ( EXTRACT ( YEAR FROM dossiers.dtdemrsa ) ) <= {$args['annee']}
					AND ( orientsstructs.rgorient IS NULL OR orientsstructs.rgorient = 1 )
				
				<COLONNE>

				GROUP BY age_range
				ORDER BY age_range ASC			
			";			

			$colonnes = array(
				1 => "", // Seulement le champ Droit et Devoirs.
				2 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient LIKE 'Emploi%' )",
				3 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient NOT LIKE 'Emploi%' )",
			 	4 => "AND orientsstructs.statut_orient = 'En attente'"
			);
			$resultats = array();
			foreach( $colonnes as $keyCol => $colonne)
			{
				$sqlFound = $this->query( preg_replace('#<COLONNE>#', $colonne, $globalQuery) );	
				$results_tous = array();
				foreach( $sqlFound as $result) {
					
					$results_tous[$result[0]['age_range']] = $result[0]['count'];
				}
				$resultats[$keyCol] = $results_tous;
			}
			return $resultats;
		}

		/**
		 * Calcul des indicateurs d'orientation pour le bloc 'anciennete'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function _indicOrientAnciennete($args) {
			$globalQuery = "
				SELECT
					(
						CASE
							WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' ) THEN 'moins de 6 mois'
							WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH ) THEN '6 mois et moins 1 an'
							WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR ) THEN '1 an et moins de 2 ans'
							WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR ) THEN '2 ans et moins de 5 ans'
							WHEN dossiers.dtdemrsa < ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR ) THEN '5 ans et plus'
							ELSE 'NC'
						END
					) AS anciennete_range,
					COUNT(DISTINCT(personnes.id)) AS count
				FROM personnes
					INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
					INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
					INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
					LEFT OUTER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
				WHERE
					calculsdroitsrsa.toppersdrodevorsa = '1'
					AND ( EXTRACT ( YEAR FROM dossiers.dtdemrsa ) ) <= {$args['annee']}
					AND ( orientsstructs.rgorient IS NULL OR orientsstructs.rgorient = 1 )
				
				<COLONNE>

				GROUP BY anciennete_range
				ORDER BY anciennete_range ASC			
			";			
			
			$colonnes = array(
			1 => "", // Seulement le champ Droit et Devoirs.
			2 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient LIKE 'Emploi%' )",
			3 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient NOT LIKE 'Emploi%' )",
			4 => "AND orientsstructs.statut_orient = 'En attente'"
			);
			$resultats = array();
			foreach( $colonnes as $keyCol => $colonne)
			{
				$sqlFound = $this->query( preg_replace('#<COLONNE>#', $colonne, $globalQuery) );
				$results_tous = array();
				foreach( $sqlFound as $result) {
						
					$results_tous[$result[0]['anciennete_range']] = $result[0]['count'];
				}
				$resultats[$keyCol] = $results_tous;
			}
			return $resultats;	
		}

		/**
		 * Calcul des indicateurs d'orientation pour le bloc 'situation'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function _indicOrientSituation($args) {
			$globalQuery = "
				SELECT
				(
					CASE
						WHEN (
							personnes.sexe = '1'
							AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
						) THEN '01 - Homme seul sans enfant'
						WHEN (
							personnes.sexe = '2'
							AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
						) THEN '02 - Femme seule sans enfant'
						WHEN (
							personnes.sexe = '1'
							AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
							AND EXISTS(
								SELECT * FROM detailsdroitsrsa
									INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
									WHERE
										detailsdroitsrsa.dossier_id = foyers.dossier_id
										AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
							)
						) THEN '03 - Homme seul avec enfant, RSA majoré'
						WHEN (
							personnes.sexe = '1'
							AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
							AND NOT EXISTS(
								SELECT * FROM detailsdroitsrsa
									INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
									WHERE
										detailsdroitsrsa.dossier_id = foyers.dossier_id
										AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
							)
						) THEN '04 - Homme seul avec enfant, RSA non majoré'
						WHEN (
							personnes.sexe = '2'
							AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
							AND EXISTS(
								SELECT * FROM detailsdroitsrsa
									INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
									WHERE
										detailsdroitsrsa.dossier_id = foyers.dossier_id
										AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
							)
						) THEN '05 - Femme seule avec enfant, RSA majoré'
						WHEN (
							personnes.sexe = '2'
							AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
							AND NOT EXISTS(
								SELECT * FROM detailsdroitsrsa
									INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
									WHERE
										detailsdroitsrsa.dossier_id = foyers.dossier_id
										AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
							)
						) THEN '06 - Femme seule avec enfant, RSA non majoré'
						WHEN (
							personnes.sexe = '1'
							AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
						) THEN '07 - Homme en couple sans enfant'
						WHEN (
							personnes.sexe = '2'
							AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
						) THEN '08 - Femme en couple sans enfant'
						WHEN (
							personnes.sexe = '1'
							AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
						) THEN '09 - Homme en couple avec enfant'
						WHEN (
							personnes.sexe = '2'
							AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
							AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
						) THEN '10 - Femme en couple avec enfant'
		
						ELSE '11 - Non connue'
					END
				) AS sitfam_range,
				COUNT(DISTINCT(personnes.id)) AS count
				FROM personnes
					INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
					INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
					INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
					LEFT OUTER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
				WHERE
					calculsdroitsrsa.toppersdrodevorsa = '1'
					AND ( EXTRACT ( YEAR FROM dossiers.dtdemrsa ) ) <= {$args['annee']}
					AND ( orientsstructs.rgorient IS NULL OR orientsstructs.rgorient = 1 )
				
				<COLONNE>

				GROUP BY sitfam_range
				ORDER BY sitfam_range ASC			
			";			
			
			$colonnes = array(
			1 => "", // Seulement le champ Droit et Devoirs.
			2 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient LIKE 'Emploi%' )",
			3 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient NOT LIKE 'Emploi%' )",
			4 => "AND orientsstructs.statut_orient = 'En attente'"
			);
			$resultats = array();
			foreach( $colonnes as $keyCol => $colonne)
			{
				$sqlFound = $this->query( preg_replace('#<COLONNE>#', $colonne, $globalQuery) );
				$results_tous = array();
				foreach( $sqlFound as $result) {
						
					$results_tous[$result[0]['sitfam_range']] = $result[0]['count'];
				}
				$resultats[$keyCol] = $results_tous;
			}
			return $resultats;			
		}

			/**
		 * Calcul des indicateurs d'orientation pour le bloc 'formation'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicOrientFormation($args) {
			$globalQuery = "
				SELECT
					(
						CASE
							WHEN dsps.nivetu IN ( '1205', '1206', '1207' ) THEN 'Vbis et VI'
							WHEN dsps.nivetu IN ( '1204' ) THEN 'V'
							WHEN dsps.nivetu IN ( '1203' ) THEN 'IV'
							WHEN dsps.nivetu IN ( '1201', '1202') THEN 'III, II, I'
							ELSE 'NC'
						END
					) AS formation_range,
					COUNT(DISTINCT(personnes.id)) AS count
				FROM personnes
					INNER JOIN dsps ON (dsps.personne_id = personnes.id )
					INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
					INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
					INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
					LEFT OUTER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
				WHERE
					calculsdroitsrsa.toppersdrodevorsa = '1'
					AND ( EXTRACT ( YEAR FROM dossiers.dtdemrsa ) ) <= {$args['annee']}
					AND ( orientsstructs.rgorient IS NULL OR orientsstructs.rgorient = 1 )
				
				<COLONNE>

				GROUP BY formation_range
				ORDER BY formation_range ASC			
			";			
			
			$colonnes = array(
			1 => "", // Seulement le champ Droit et Devoirs.
			2 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient LIKE 'Emploi%' )",
			3 => "AND orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.lib_type_orient NOT LIKE 'Emploi%' )",
			4 => "AND orientsstructs.statut_orient = 'En attente'"
			);
			$resultats = array();
			foreach( $colonnes as $keyCol => $colonne)
			{
				$sqlFound = $this->query( preg_replace('#<COLONNE>#', $colonne, $globalQuery) );
				$results_tous = array();
				foreach( $sqlFound as $result) {
						
					$results_tous[$result[0]['formation_range']] = $result[0]['count'];
				}
				$resultats[$keyCol] = $results_tous;
			}
			return $resultats;			
		}

		//##############################################################################
		//
		// INDICATEURS DE CARACTÉRISTIQUES DES CONTRATS :
		//
		//##############################################################################

		/**
		 * Calcul des indicateurs de caractéristiques des contrats.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function indicateursCaracteristiquesContrats($args) {
			set_time_limit(0);
			$resultats = array();
			$resultats['R1Total'] = $this->_indicCaraContratsR1Total($args);
			$resultats['R1DD'] = $this->_indicCaraContratsR1DD($args);
			$resultats['R2Total'] = $this->_indicCaraContratsR2Total($args);
			$resultats['R2DD'] = $this->_indicCaraContratsR2DD($args);
			$resultats['R3Total'] = $this->_indicCaraContratsR3Total($args);
			$resultats['R3DD'] = $this->_indicCaraContratsR3DD($args);
			$resultats['R4'] = $this->_indicCaraContratsR4($args);
			$resultats['R5'] = $this->_indicCaraContratsR5($args);
			return $resultats;
		}

		protected function _indicCaraContratsR1Total($args) {
			return array( "Non géré");
		}

		protected function _indicCaraContratsR1DD($args) {
			return array( "Non géré", "Non géré");
		}

		protected function _indicCaraContratsR2Total($args) {
			return array( "Non géré");
		}

		protected function _indicCaraContratsR2DD($args) {
			return array( "Non géré", "Non géré");
		}

		protected function _indicCaraContratsR3Total($args) {
			$blocs = array(
				" ",
				null,
				" AND sr.typeorient_id IN ( {$this->isEmploi} ) ",
				" AND sr.typeorient_id IN ( {$this->isSocial} ) ",
			);
			foreach( $blocs as $keyRow => $bloc) {
				if( is_null($bloc) ) {
					$resultats[$keyRow] = "Non géré";
				}
				else {
					$blocSQL = "
						SELECT count(pe.id)
						FROM
							personnes pe
							LEFT JOIN contratsinsertion ci ON pe.id = ci.personne_id,
							structuresreferentes sr
						WHERE 
							sr.id = ci.structurereferente_id
							AND ( EXTRACT ( YEAR FROM ci.df_ci ) ) <= {$args['annee']}
							AND ( EXTRACT ( YEAR FROM ci.datevalidation_ci ) ) <= {$args['annee']}
							AND ci.rg_ci = (
								SELECT MAX(ci2.rg_ci)
								FROM contratsinsertion ci2
								WHERE pe.id = ci2.personne_id 
							)
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
		}

		protected function _indicCaraContratsR3DD($args) {
			$filtres = array(
				" AND cdr.toppersdrodevorsa = '1' ",
				" AND cdr.toppersdrodevorsa = '0' "
			);
			$blocs = array(
				" ",
				null,
				" AND sr.typeorient_id IN ({$this->isEmploi}) ",
				" AND sr.typeorient_id IN ({$this->isSocial}) ",
			);

			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					if( is_null($bloc) ) {
						$resultats[$keyRow][$keyCol] = "Non géré";
					}
					else {
						$blocSQL = "
							SELECT count(pe.id)
							FROM
								personnes pe
								LEFT JOIN contratsinsertion ci ON pe.id = ci.personne_id,
								structuresreferentes sr,
								foyers fo,
								dossiers dr,
								calculsdroitsrsa cdr,
								orientsstructs os
							WHERE 
								sr.id = ci.structurereferente_id
								AND ( EXTRACT ( YEAR FROM ci.df_ci ) ) <= {$args['annee']}
								AND ( EXTRACT ( YEAR FROM ci.datevalidation_ci ) ) <= {$args['annee']}
								AND ci.rg_ci = (
									SELECT MAX(ci2.rg_ci)
									FROM contratsinsertion ci2
									WHERE pe.id = ci2.personne_id 
								)
								{$bloc}
								AND pe.id = cdr.personne_id
								AND pe.foyer_id = fo.id
								AND dr.id = fo.dossier_id
								AND pe.id = os.personne_id
								AND ( EXTRACT ( YEAR FROM dr.dtdemrsa ) ) <= {$args['annee']}
								{$filtre}
						;";
						$sqlFound = $this->query( $blocSQL );
						$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
					}
				}
			}
			return $resultats;
		}

		protected function _indicCaraContratsR4($args) {
			$blocs = array(
				" AND ci.duree_engag < 6 ",
				" AND ci.duree_engag >= 6 AND ci.duree_engag < 12 ",
				" AND ci.duree_engag >= 12 "
			);
			foreach( $blocs as $keyRow => $bloc) {
				if( is_null($bloc) ) {
					$resultats[$keyRow] = "Non géré";
				}
				else {
					$blocSQL = "
						SELECT count(pe.id)
						FROM
							personnes pe
							LEFT JOIN contratsinsertion ci ON pe.id = ci.personne_id,
							structuresreferentes sr
						WHERE 
							sr.id = ci.structurereferente_id
							AND ( EXTRACT ( YEAR FROM ci.df_ci ) ) <= {$args['annee']}
							AND ( EXTRACT ( YEAR FROM ci.datevalidation_ci ) ) <= {$args['annee']}
							AND ci.rg_ci = (
								SELECT MAX(ci2.rg_ci)
								FROM contratsinsertion ci2
								WHERE pe.id = ci2.personne_id 
							)
							AND sr.typeorient_id IN ({$this->isEmploi}) 
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
		}

		protected function _indicCaraContratsR5($args) {
			$blocs = array(
				" AND ci.duree_engag < 6 ",
				" AND ci.duree_engag >= 6 AND ci.duree_engag < 12 ",
				" AND ci.duree_engag >= 12 "
			);
			foreach( $blocs as $keyRow => $bloc) {
				if( is_null($bloc) ) {
					$resultats[$keyRow] = "Non géré";
				}
				else {
					$blocSQL = "
						SELECT count(pe.id)
						FROM
							personnes pe
							LEFT JOIN contratsinsertion ci ON pe.id = ci.personne_id,
							structuresreferentes sr
						WHERE 
							sr.id = ci.structurereferente_id
							AND ( EXTRACT ( YEAR FROM ci.df_ci ) ) <= {$args['annee']}
							AND ( EXTRACT ( YEAR FROM ci.datevalidation_ci ) ) <= {$args['annee']}
							AND ci.rg_ci = (
								SELECT MAX(ci2.rg_ci)
								FROM contratsinsertion ci2
								WHERE pe.id = ci2.personne_id 
							)
							AND sr.typeorient_id IN ({$this->isSocial}) 
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
		}

		//##############################################################################
		//
		// INDICATEURS DE NATURE DES CONTRATS :
		//
		//##############################################################################

		/**
		 * Calcul des indicateurs de nature des contrats.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function indicateursNatureContrats($args) {
			set_time_limit(0);
			$filtres = array(
				" AND ci.engag_object IN ( '45', '46', '53') ", // a01 Activités, stages ou formation destinés à acquérir des compétences professionnelles
				" AND ci.engag_object IN ( '41', '42', '48', '51') ", // a02 Orientation vers le service public de l'emploi, parcours de recherche d'emploi
				" AND ci.engag_object IN ( '43', '54', '57') ", // a03 Mesures d'insertion par l'activité économique (IAE)
				" AND ci.engag_object IN ( '55' ) ", // a04 Aide à la réalisation d’un projet de création, de reprise ou de poursuite d’une activité non salariée
				" AND ci.engag_object IN ( '52', '56') ", // a05 Emploi aidé
				" AND ci.engag_object IN ( '58', '59') ", // a06 Emploi non aidé
				" AND ci.engag_object IN ( '26', '29') ", // a07 Actions facilitant le lien social (développement de l'autonomie sociale, activités collectives,…)
				" AND ci.engag_object IN ( '44' ) ", // a08 Actions facilitant la mobilité (permis de conduire, acquisition / location de véhicule, frais de transport…)
				" AND ci.engag_object IN ( '05', '06', '31') ", // a09 Actions visant l'accès à un logement, relogement ou à l'amélioration de l'habitat
				" AND ci.engag_object IN ( '21') ", // a10 Actions facilitant l'accès aux soins
				" AND ci.engag_object IN ( '07', '33') ", // a11 Actions visant l'autonomie financière (constitution d'un dossier de surendettement,...)
				" AND ci.engag_object IN ( '1P', '1F', '02', '03', '04', '23') ", // a12 Actions visant la famille et la parentalité (soutien familiale, garde d'enfant, …)
				" AND ci.engag_object IN ( '22') ", // a13 Lutte contre l'illettrisme ; acquisition des savoirs de base
				" AND ci.engag_object IN ( '10', '24') "  // a14 Autres actions
			);
			$blocs = array(
				"L262-35" => " AND sr.typeorient_id IN ({$this->isEmploi}) ",
				"L262-36" => " AND sr.typeorient_id IN ({$this->isSocial}) "
			);

			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					if( is_null($bloc) ) {
						$resultats[$keyRow][$keyCol] = "Non géré";
					}
					else {
						$blocSQL = "
							SELECT count(pe.id)
							FROM
								personnes pe
								LEFT JOIN contratsinsertion ci ON pe.id = ci.personne_id,
								structuresreferentes sr
							WHERE 
								sr.id = ci.structurereferente_id
								AND ( EXTRACT ( YEAR FROM ci.df_ci ) ) <= {$args['annee']}
								AND ( EXTRACT ( YEAR FROM ci.datevalidation_ci ) ) <= {$args['annee']}
								AND ci.rg_ci = (
									SELECT MAX(ci2.rg_ci)
									FROM contratsinsertion ci2
									WHERE pe.id = ci2.personne_id 
								)
								{$bloc}

								{$filtre}
						;";
						$sqlFound = $this->query( $blocSQL );
						$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
					}
				}
			}
			return $resultats;
		}

		//##############################################################################
		//
		// INDICATEURS DE DÉLAIS :
		//
		//##############################################################################

		/**
		 * Calcul des indicateurs de délais.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function indicateursDelais($args) { 
			set_time_limit(0);
			$resultats = array();
			$resultats['Amoy'] = $this->_indicDelaisAmoy($args);
			$resultats['Bmoy'] = $this->_indicDelaisBmoy($args);
			$resultats['Btot'] = $this->_indicDelaisBtot($args);
			return $resultats;
		}

		protected function _indicDelaisAmoy($args) {
			set_time_limit(0);
			$blocSQL = "
				SELECT
					avg(os.date_valid - dr.dtdemrsa) 
				FROM
				 	dossiers dr,
				 	orientsstructs os,
				 	personnes pe,
				 	foyers fo
				WHERE
				 	pe.foyer_id = fo.id
				 	AND dr.id = fo.dossier_id
				 	AND pe.id = os.personne_id
				 	AND ( EXTRACT ( YEAR FROM dr.dtdemrsa ) ) = '{$args['annee']}'
				 	AND dr.dtdemrsa <= os.date_valid
			;";
			$sqlFound = $this->query( $blocSQL );
			$resultats = round($sqlFound[0][0]['avg']);
			return $resultats;
		}

		protected function _indicDelaisBmoy($args) {
			$blocs = array(
				" ",
				null,
				" AND os.typeorient_id IN ({$this->isEmploi}) ",
				" AND os.typeorient_id IN ({$this->isSocial}) ",
			);
			foreach( $blocs as $keyRow => $bloc)
			{
				if( is_null($bloc) )
				{
					$resultats[$keyRow] = "Non géré";
				}
				else
				{
					$blocSQL = "
						SELECT
							avg(ci.date_saisi_ci - os.date_valid) 
							-- os.date_valid, ci.date_saisi_ci, (ci.date_saisi_ci - os.date_valid)
						FROM
							orientsstructs os,
							contratsinsertion ci,
							personnes pe
						WHERE
							pe.id = ci.personne_id
							AND pe.id = os.personne_id
							AND ( EXTRACT ( YEAR FROM os.date_valid ) ) = '{$args['annee']}'
							AND os.date_valid <= ci.date_saisi_ci
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = round($sqlFound[0][0]['avg']);
				}
			}
			return $resultats;
		}

		protected function _indicDelaisBtot($args) {
			set_time_limit(0);
			$filtres = array(
				" ",
				" AND ( ci.date_saisi_ci - os.date_valid ) <= '60' ",
				" AND ( ci.date_saisi_ci - os.date_valid ) BETWEEN '61' AND '120' ",
				" AND ( ci.date_saisi_ci - os.date_valid ) >= '121' "
			);
			$blocs = array(
				null,
				"L262-35" => " AND os.typeorient_id IN ({$this->isEmploi}) ",
				"L262-36" => " AND os.typeorient_id IN ({$this->isSocial}) "
			);

			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					if( is_null($bloc) ) {
						$resultats[$keyRow][$keyCol] = "Non géré";
					}
					else {
						$blocSQL = "
						SELECT
							count(*)
						FROM
							orientsstructs os,
							contratsinsertion ci,
							personnes pe
						WHERE
							pe.id = ci.personne_id
							AND pe.id = os.personne_id
							AND ( EXTRACT ( YEAR FROM os.date_valid ) ) = '{$args['annee']}'
							AND ( os.date_valid <= ci.date_saisi_ci )
							{$bloc}
							{$filtre}
						;";
						$sqlFound = $this->query( $blocSQL );
						$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
					}
				}
			}
			return $resultats;
		}

		//##############################################################################
		//
		// INDICATEURS D'ORGANISMES :
		//
		//##############################################################################

		public function indicateursOrganismes($args) {
			// Nombre de personnes dans le champ des Droits et Devoirs (L262-28) au 31 décembre de l'année
			// ET qui ont un référent.
			$sql = 'SELECT count(*), typesorients.id, typesorients.parentid, typesorients.lib_type_orient
						FROM personnes
						LEFT JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
						LEFT JOIN personnes_referents ON (personnes_referents.personne_id = personnes.id)
						LEFT JOIN structuresreferentes ON ( structuresreferentes.id = personnes_referents.structurereferente_id )
						LEFT JOIN typesorients ON ( typesorients.id = structuresreferentes.typeorient_id  )
						WHERE calculsdroitsrsa.toppersdrodevorsa = \'1\'
						GROUP BY typesorients.id, typesorients.parentid, typesorients.lib_type_orient
						ORDER BY typesorients.id;';
			$sqlFound = $this->query( $sql );

			$fieldName = Configure::read('with_parentid') ? 'parentid' : 'id';

			$results = array();
			$results['DroitsEtDevoirs'] = 0; 
			$results['Autres'] = $results['SP'] = $results['SSD'] = $results['PE'] = 0;
			foreach($sqlFound as $row)
			{
				if( empty($row[0][$fieldName]) ) continue;
				$results['DroitsEtDevoirs'] += $row[0]['count'];

				if( preg_match('/emploi/i', $row[0]['lib_type_orient']))
					$results['PE'] += $row[0]['count']; // PE : emploi
				elseif( preg_match('/prépro/i', $row[0]['lib_type_orient']))
					$results['SP'] += $row[0]['count'];//SP : Socio Professionelle
				elseif( preg_match('/social/i', $row[0]['lib_type_orient']))
					$results['SSD'] += $row[0]['count'];//SSD : Service Social du Département
				else // autres
					$results['Autres'] += $row[0]['count'];
			}

			return $results;
		}

		//##############################################################################
		//
		// INDICATEURS DE RÉORIENTATIONS :
		//
		//##############################################################################

		/**
		 * Calcul des indicateurs de réorientation.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function indicateursReorientations($args) {
			set_time_limit(0);
			$resultats = array();
			$resultats['age'] = $this->_indicReorientAge($args);
			$resultats['situation'] = $this->_indicReorientSituation($args);
			$resultats['formation'] = $this->_indicReorientFormation($args);
			$resultats['anciennete'] = $this->_indicReorientAnciennete($args);
			return $resultats;
		}

		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'age'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicReorientAge($args) {
			$globalQuery = "
			SELECT
				(
					CASE
						WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 0 AND 24 THEN '0 - 24'
						WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 25 AND 29 THEN '25 - 29'
						WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 30 AND 39 THEN '30 - 39'
						WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 40 AND 49 THEN '40 - 49'
						WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) BETWEEN 50 AND 59 THEN '50 - 59'
						WHEN EXTRACT( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', personnes.dtnai ) ) >= 60 THEN '>= 60'
						ELSE 'NC'
					END
				) AS age_range,
				COUNT(DISTINCT(personnes.id)) AS count
				FROM personnes
					INNER JOIN orientsstructs AS derniereorientstruct ON ( derniereorientstruct.personne_id = personnes.id )
					INNER JOIN orientsstructs AS avantderniereorientstruct ON ( avantderniereorientstruct.personne_id = personnes.id )
				WHERE
					derniereorientstruct.statut_orient = 'Orienté'
					AND avantderniereorientstruct.statut_orient = 'Orienté'
					AND derniereorientstruct.id IN (
						SELECT orientsstructs.id
							FROM orientsstructs
							WHERE
								orientsstructs.personne_id = personnes.id
								AND orientsstructs.statut_orient = 'Orienté'
								AND EXTRACT( YEAR FROM ( orientsstructs.date_valid ) ) = {$args['annee']}
							ORDER BY date_valid DESC
							LIMIT 1
					)
					AND derniereorientstruct.rgorient > 1
					AND avantderniereorientstruct.id IN (
						SELECT orientsstructs.id
							FROM orientsstructs
							WHERE
								orientsstructs.personne_id = personnes.id
								AND orientsstructs.rgorient < derniereorientstruct.rgorient
								AND orientsstructs.statut_orient = 'Orienté'
							ORDER BY date_valid DESC
							LIMIT 1
					)
					<EXTENDQUERY>
				GROUP BY age_range
				ORDER BY age_range ASC";
			
			$extendQuery = "
			AND derniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT1> LIKE 'Emploi%'
			)
			AND avantderniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT2> LIKE 'Emploi%'
			)";
			
			$sqlFound_tous = $this->query( preg_replace('#<EXTENDQUERY>#', '', $globalQuery) );
			$results_tous = array();
			foreach( $sqlFound_tous as $result) {
				$results_tous[$result[0]['age_range']] = $result[0]['count'];
			}

			$query = preg_replace('#<NOT1>#', 'NOT', $extendQuery);
			$query = preg_replace('#<NOT2>#', '', $query);
			$sqlFound_social = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );
			$results_social = array();
			foreach( $sqlFound_social as $result) {
				$results_social[$result[0]['age_range']] = $result[0]['count'];
			}			
			
			$query = preg_replace('#<NOT1>#', '', $extendQuery);
			$query = preg_replace('#<NOT2>#', 'NOT', $query);
			$sqlFound_pro = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );			
			$results_pro = array();
			foreach( $sqlFound_pro as $result) {
				$results_pro[$result[0]['age_range']] = $result[0]['count'];
			}			
			return array('tous'=>$results_tous, 'versSocial'=>$results_social, 'versPro'=>$results_pro);
		}
		
		
		

		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'situation'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicReorientSituation($args) {
			$globalQuery = "
			SELECT
			(
				CASE
					WHEN (
						personnes.sexe = '1'
						AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
					) THEN '01 - Homme seul sans enfant'
					WHEN (
						personnes.sexe = '2'
						AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
					) THEN '02 - Femme seule sans enfant'
					WHEN (
						personnes.sexe = '1'
						AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
						AND EXISTS(
							SELECT * FROM detailsdroitsrsa
								INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
								WHERE
									detailsdroitsrsa.dossier_id = foyers.dossier_id
									AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
						)
					) THEN '03 - Homme seul avec enfant, RSA majoré'
					WHEN (
						personnes.sexe = '1'
						AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
						AND NOT EXISTS(
							SELECT * FROM detailsdroitsrsa
								INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
								WHERE
									detailsdroitsrsa.dossier_id = foyers.dossier_id
									AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
						)
					) THEN '04 - Homme seul avec enfant, RSA non majoré'
					WHEN (
						personnes.sexe = '2'
						AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
						AND EXISTS(
							SELECT * FROM detailsdroitsrsa
								INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
								WHERE
									detailsdroitsrsa.dossier_id = foyers.dossier_id
									AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
						)
					) THEN '05 - Femme seule avec enfant, RSA majoré'
					WHEN (
						personnes.sexe = '2'
						AND foyers.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
						AND NOT EXISTS(
							SELECT * FROM detailsdroitsrsa
								INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
								WHERE
									detailsdroitsrsa.dossier_id = foyers.dossier_id
									AND detailscalculsdroitsrsa.natpf IN ( 'RCI', 'RSI' )
						)
					) THEN '06 - Femme seule avec enfant, RSA non majoré'
					WHEN (
						personnes.sexe = '1'
						AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
					) THEN '07 - Homme en couple sans enfant'
					WHEN (
						personnes.sexe = '2'
						AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) = 0
					) THEN '08 - Femme en couple sans enfant'
					WHEN (
						personnes.sexe = '1'
						AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
					) THEN '09 - Homme en couple avec enfant'
					WHEN (
						personnes.sexe = '2'
						AND foyers.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')
						AND ( SELECT COUNT(*) FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = 'RSA' ) WHERE enfants.foyer_id = foyers.id AND prestations.rolepers = 'ENF' ) > 0
					) THEN '10 - Femme en couple avec enfant'
	
					ELSE '11 - Non connue'
				END
			) AS sitfam_range,
			COUNT(DISTINCT(personnes.id)) AS count
		FROM personnes
			INNER JOIN orientsstructs AS derniereorientstruct ON ( derniereorientstruct.personne_id = personnes.id )
			INNER JOIN orientsstructs AS avantderniereorientstruct ON ( avantderniereorientstruct.personne_id = personnes.id )
			INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
			LEFT OUTER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
		WHERE
			derniereorientstruct.statut_orient = 'Orienté'
			AND avantderniereorientstruct.statut_orient = 'Orienté'
			AND derniereorientstruct.id IN (
				SELECT orientsstructs.id
					FROM orientsstructs
					WHERE
						orientsstructs.personne_id = personnes.id
						AND orientsstructs.statut_orient = 'Orienté'
						AND EXTRACT( YEAR FROM ( orientsstructs.date_valid ) ) = {$args['annee']}
					ORDER BY date_valid DESC
					LIMIT 1
			)
			AND derniereorientstruct.rgorient > 1
			AND avantderniereorientstruct.id IN (
				SELECT orientsstructs.id
					FROM orientsstructs
					WHERE
						orientsstructs.personne_id = personnes.id
						AND orientsstructs.rgorient < derniereorientstruct.rgorient
						AND orientsstructs.statut_orient = 'Orienté'
					ORDER BY date_valid DESC
					LIMIT 1
			)
			<EXTENDQUERY>
		GROUP BY sitfam_range
		ORDER BY sitfam_range ASC
		";
			
			
			$extendQuery = "
			AND derniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT1> LIKE 'Emploi%'
			)
			AND avantderniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT2> LIKE 'Emploi%'
			)		
			";
			
			$sqlFound_tous = $this->query( preg_replace('#<EXTENDQUERY>#', '', $globalQuery) );
			$results_tous = array();
			foreach( $sqlFound_tous as $result) {
				$results_tous[$result[0]['sitfam_range']] = $result[0]['count'];
			}
			
			$query = preg_replace('#<NOT1>#', 'NOT', $extendQuery);
			$query = preg_replace('#<NOT2>#', '', $query);
			$sqlFound_social = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );
			$results_social = array();
			foreach( $sqlFound_social as $result) {
				$results_social[$result[0]['sitfam_range']] = $result[0]['count'];
			}
				
			$query = preg_replace('#<NOT1>#', '', $extendQuery);
			$query = preg_replace('#<NOT2>#', 'NOT', $query);
			$sqlFound_pro = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );
			$results_pro = array();
			foreach( $sqlFound_pro as $result) {
				$results_pro[$result[0]['sitfam_range']] = $result[0]['count'];
			}
			return array('tous'=>$results_tous, 'versSocial'=>$results_social, 'versPro'=>$results_pro);			
		}

		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'formation'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicReorientFormation($args) {
			$globalQuery = "
			SELECT
				(
					CASE
						WHEN dsps.nivetu IN ( '1205', '1206', '1207' ) THEN 'Vbis et VI'
						WHEN dsps.nivetu IN ( '1204' ) THEN 'V'
						WHEN dsps.nivetu IN ( '1203' ) THEN 'IV'
						WHEN dsps.nivetu IN ( '1201', '1202') THEN 'III, II, I'
						ELSE 'NC'
					END
				) AS formation_range,
				COUNT(DISTINCT(personnes.id)) AS count
			FROM personnes
				INNER JOIN orientsstructs AS derniereorientstruct ON ( derniereorientstruct.personne_id = personnes.id )
				INNER JOIN orientsstructs AS avantderniereorientstruct ON ( avantderniereorientstruct.personne_id = personnes.id )
				INNER JOIN dsps ON (dsps.personne_id = personnes.id )
			WHERE
				derniereorientstruct.statut_orient = 'Orienté'
				AND avantderniereorientstruct.statut_orient = 'Orienté'
				AND derniereorientstruct.id IN (
					SELECT orientsstructs.id
						FROM orientsstructs
						WHERE
							orientsstructs.personne_id = personnes.id
							AND orientsstructs.statut_orient = 'Orienté'
							AND EXTRACT( YEAR FROM ( orientsstructs.date_valid ) ) = {$args['annee']}
						ORDER BY date_valid DESC
						LIMIT 1
				)
				AND derniereorientstruct.rgorient > 1
				AND avantderniereorientstruct.id IN (
					SELECT orientsstructs.id
						FROM orientsstructs
						WHERE
							orientsstructs.personne_id = personnes.id
							AND orientsstructs.rgorient < derniereorientstruct.rgorient
							AND orientsstructs.statut_orient = 'Orienté'
						ORDER BY date_valid DESC
						LIMIT 1
				)
				<EXTENDQUERY>
			GROUP BY formation_range
			ORDER BY formation_range ASC			
			";
			
			$extendQuery = "
			AND derniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT1> LIKE 'Emploi%'
			)
			AND avantderniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT2> LIKE 'Emploi%'
			)";
				
			$sqlFound_tous = $this->query( preg_replace('#<EXTENDQUERY>#', '', $globalQuery) );
			$results_tous = array();
			foreach( $sqlFound_tous as $result) {
				$results_tous[$result[0]['formation_range']] = $result[0]['count'];
			}
			
			$query = preg_replace('#<NOT1>#', 'NOT', $extendQuery);
			$query = preg_replace('#<NOT2>#', '', $query);
			$sqlFound_social = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );
			$results_social = array();
			foreach( $sqlFound_social as $result) {
				$results_social[$result[0]['formation_range']] = $result[0]['count'];
			}
				
			$query = preg_replace('#<NOT1>#', '', $extendQuery);
			$query = preg_replace('#<NOT2>#', 'NOT', $query);
			$sqlFound_pro = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );
			$results_pro = array();
			foreach( $sqlFound_pro as $result) {
				$results_pro[$result[0]['formation_range']] = $result[0]['count'];
			}
			return array('tous'=>$results_tous, 'versSocial'=>$results_social, 'versPro'=>$results_pro);			
		}

		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'anciennete'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicReorientAnciennete($args) {
			$globalQuery = "
			SELECT
				(
					CASE
						WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' ) THEN 'moins de 6 mois'
						WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH ) THEN '6 mois et moins 1 an'
						WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR ) THEN '1 an et moins de 2 ans'
						WHEN dossiers.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR ) THEN '2 ans et moins de 5 ans'
						WHEN dossiers.dtdemrsa < ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR ) THEN '5 ans et plus'
						ELSE 'NC'
					END
				) AS anciennete_range,
				COUNT(DISTINCT(personnes.id)) AS count
			FROM personnes
				INNER JOIN orientsstructs AS derniereorientstruct ON ( derniereorientstruct.personne_id = personnes.id )
				INNER JOIN orientsstructs AS avantderniereorientstruct ON ( avantderniereorientstruct.personne_id = personnes.id )
				INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
				INNER JOIN dossiers ON (foyers.dossier_id = dossiers.id )
			WHERE
				derniereorientstruct.statut_orient = 'Orienté'
				AND avantderniereorientstruct.statut_orient = 'Orienté'
				AND derniereorientstruct.id IN (
					SELECT orientsstructs.id
						FROM orientsstructs
						WHERE
							orientsstructs.personne_id = personnes.id
							AND orientsstructs.statut_orient = 'Orienté'
							AND EXTRACT( YEAR FROM ( orientsstructs.date_valid ) ) = {$args['annee']} -- FIXME: param
						ORDER BY date_valid DESC
						LIMIT 1
				)
				AND derniereorientstruct.rgorient > 1
				AND avantderniereorientstruct.id IN (
					SELECT orientsstructs.id
						FROM orientsstructs
						WHERE
							orientsstructs.personne_id = personnes.id
							AND orientsstructs.rgorient < derniereorientstruct.rgorient
							AND orientsstructs.statut_orient = 'Orienté'
						ORDER BY date_valid DESC
						LIMIT 1
				)
				<EXTENDQUERY>
			GROUP BY anciennete_range
			ORDER BY anciennete_range ASC
			";			
			
			$extendQuery = "
			AND derniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT1> LIKE 'Emploi%'
			)
			AND avantderniereorientstruct.typeorient_id IN (
				SELECT typesorients.id
					FROM typesorients
					WHERE typesorients.lib_type_orient <NOT2> LIKE 'Emploi%'
			)";
			
			$sqlFound_tous = $this->query( preg_replace('#<EXTENDQUERY>#', '', $globalQuery) );
			$results_tous = array();
			foreach( $sqlFound_tous as $result) {
				$results_tous[$result[0]['anciennete_range']] = $result[0]['count'];
			}
				
			$query = preg_replace('#<NOT1>#', 'NOT', $extendQuery);
			$query = preg_replace('#<NOT2>#', '', $query);
			$sqlFound_social = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );
			$results_social = array();
			foreach( $sqlFound_social as $result) {
				$results_social[$result[0]['anciennete_range']] = $result[0]['count'];
			}
			
			$query = preg_replace('#<NOT1>#', '', $extendQuery);
			$query = preg_replace('#<NOT2>#', 'NOT', $query);
			$sqlFound_pro = $this->query( preg_replace('#<EXTENDQUERY>#', $query, $globalQuery) );
			$results_pro = array();
			foreach( $sqlFound_pro as $result) {
				$results_pro[$result[0]['anciennete_range']] = $result[0]['count'];
			}
			return array('tous'=>$results_tous, 'versSocial'=>$results_social, 'versPro'=>$results_pro);			
		}

		//##############################################################################
		//
		// INDICATEURS MOTIFS DE RÉORIENTAITONS :
		//
		//##############################################################################

		/**
		 * Calcul des indicateurs motifs de réorientations.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		public function indicateursMotifsReorientations($args) {
			set_time_limit(0);
			$resultats = array();
			$resultats['tab1'] = $this->_indicMotifsReorientTab1($args);
			$resultats['tab2'] = $this->_indicMotifsReorientTab2($args);
			return $resultats;
		}

		protected function _indicMotifsReorientTab1($args) {
			$blocs = array(
				array("select" => true, "now"=>$this->isSocial, "before"=>$this->isEmploi),
				null, 
				null, 
				null
			);

			foreach( $blocs as $keyRow => $bloc) {
				if( is_null($bloc) ) {
					$resultats[$keyRow] = "Non géré";
				}
				else {
					$select = "";
					$where = " AND o.rgorient > 1 ";

					if( $bloc['select'] ) {
						$select = $this->now_and_before;
						$where = " 
							AND o.rgorient = noworient.rgorient
							AND noworient.typeorient_id IN ( {$bloc['now']})
							AND beforeorient.typeorient_id IN ( {$bloc['before']} )
						";
					}

					$blocSQL = "
						SELECT count(DISTINCT p.id)     
						FROM
							personnes p {$select},
							foyers f,
							dossiers d,
							calculsdroitsrsa c,
							orientsstructs o
						WHERE 
							p.id = c.personne_id
							AND p.foyer_id = f.id
							AND d.id = f.dossier_id
							AND p.id = o.personne_id
							AND ( EXTRACT ( YEAR FROM d.dtdemrsa ) ) <= {$args['annee']}
							AND c.toppersdrodevorsa = '1'
							AND o.rgorient > 1
							{$where}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
		}

		protected function _indicMotifsReorientTab2($args) {
			$blocs = array(
				array("select" => false, "now"=>"", "before"=>""),
				array("select" => true, "now"=>$this->isSocial, "before"=>$this->isSocial),
				array("select" => true, "now"=>$this->isEmploi, "before"=>$this->isSocial)
			);

			foreach( $blocs as $keyRow => $bloc) {
				if( is_null($bloc) ) {
					$resultats[$keyRow] = "Non géré";
				}
				else {
					$select = "";
					$where = " AND o.rgorient > 1 ";

					if( $bloc['select'] ) {
						$select = $this->now_and_before;
						$where = " 
							AND o.rgorient = noworient.rgorient
							AND noworient.typeorient_id IN ( {$bloc['now']})
							AND beforeorient.typeorient_id IN ( {$bloc['before']} )
						";
					}
					$blocSQL = "
						SELECT count(DISTINCT p.id)     
						FROM
							personnes p {$select},
							foyers f,
							dossiers d,
							calculsdroitsrsa c,
							orientsstructs o,
							dossierseps de,
							passagescommissionseps pc
						WHERE 
							p.id = c.personne_id
							AND p.foyer_id = f.id
							AND d.id = f.dossier_id
							AND p.id = o.personne_id
							AND ( EXTRACT ( YEAR FROM d.dtdemrsa ) ) <= {$args['annee']}
							AND c.toppersdrodevorsa = '1'
							AND de.personne_id = p.id
							AND pc.dossierep_id = de.id
							AND pc.etatdossierep = 'traite'
							{$where}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
		}
	}
?>