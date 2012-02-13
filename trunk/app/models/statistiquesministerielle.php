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
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droit et Devoirs.
				" AND o.typeorient_id IN ( {$this->isEmploi} )",
				" AND o.typeorient_id IN ( {$this->isSocial} )",
			 	" AND (o.statut_orient = 'En attente')"
			);
			$blocs = array(
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '0' AND '24' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '25' AND '29' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '30' AND '39' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '40' AND '49' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '50' AND '59' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '60' AND '999' ",
				" AND ( p.dtnai IS NULL) "
			);
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
					$blocSQL = "
						SELECT count(DISTINCT p.id)     
						FROM
							personnes p,
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
							AND ( o.rgorient IS NULL OR o.rgorient = 1 ) 
							{$filtre}
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
				}
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
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droitet Devoirs.
				" AND o.typeorient_id IN ( {$this->isEmploi} )", 
				" AND o.typeorient_id IN ( {$this->isSocial} )",
			 	" AND (o.statut_orient = 'En attente')"
			);
			$blocs = array(
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' ) ",
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH ) ",
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR ) ",
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR ) ",
				" AND d.dtdemrsa < ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR ) ",
				" AND ( d.dtdemrsa IS NULL) "
			);
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
					$blocSQL = "
						SELECT count(DISTINCT p.id)    
						FROM
							personnes p,
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
							AND ( o.rgorient IS NULL OR o.rgorient = 1 )
							{$filtre}
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
				}
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
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droit et Devoirs.
				" AND o.typeorient_id IN ( {$this->isEmploi} )",
				" AND o.typeorient_id IN ( {$this->isSocial} )",
			 	" AND (o.statut_orient = 'En attente')"
			);
			$homme = " AND (p.sexe = '1') ";
			$femme = " AND (p.sexe = '2') ";
			$seul = " AND (f.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')) ";
			$couple = " AND (f.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')) ";
			$rsaMajore = " ";
//			$rsamajore'; true (bénéficiant du rsa majoré) ou false ( ne bénficiant pas)
//			#Model=detailscalculdroitrsa.natpf#
//			true :
//				"RSI" : RSA Socle majoré (Financement sur fonds Conseil général)
//				"RCI" : RSA Activité majoré (Financement sur fonds Etat)
//			false :
//				"RSD" : RSA Socle (Financement sur fonds Conseil général)
//				"RSU" : RSA Socle Etat Contrat aidé  (Financement sur fonds Etat)
//				"RSB" : RSA Socle Local (Financement sur fonds Conseil général)
//				"RCD" : RSA Activité (Financement sur fonds Etat)
//				"RCU" : RSA Activité Etat Contrat aidé (Financement sur fonds Etat)
//				"RCB" : RSA Activité Local (Financement sur fonds Conseil général)

			$blocs = array(
				array("{$homme}{$seul}", "enfant"=>false),
				array("{$femme}{$seul}", "enfant"=>false),
				array("{$homme}{$seul}", "enfant"=>true),
				array("{$homme}{$seul}{$rsaMajore}", "enfant"=>true),
				array("{$femme}{$seul}", "enfant"=>true),
				array("{$femme}{$seul}{$rsaMajore}", "enfant"=>true),
				array("{$homme}{$couple}", "enfant"=>false),
				array("{$femme}{$couple}", "enfant"=>false),
				array("{$homme}{$couple}", "enfant"=>true),
				array("{$femme}{$couple}", "enfant"=>true),
				// non connue :
				array(" AND ( (f.sitfam = 'ABA') OR ( f.sitfam IS NULL ) OR ( f.sitfam = '' ) )", "enfant"=>false)
			);
			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					$blocSQLTotal = "
						SELECT count(DISTINCT p.id)    
						FROM
							personnes p,
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
							AND ( o.rgorient IS NULL OR o.rgorient = 1 ) 
							{$filtre}
							{$bloc[0]}
					;";
					$blocSQLEnfant = "
						SELECT count(DISTINCT p.id)    
						FROM
							personnes p,
							foyers f,
							dossiers d,
							calculsdroitsrsa c,
							orientsstructs o
							,
							( 
								SELECT 
									count(*), fo.id
								FROM 
									foyers fo
									INNER JOIN personnes pe ON pe.foyer_id = fo.id
									INNER JOIN prestations pr ON pr.personne_id = pe.id
									AND pr.natprest = 'RSA'
									AND pr.rolepers = 'ENF'
								GROUP BY fo.id 
							) enfants
						WHERE 
							p.id = c.personne_id
							AND p.foyer_id = f.id
							AND d.id = f.dossier_id
							AND p.id = o.personne_id
							AND ( EXTRACT ( YEAR FROM d.dtdemrsa ) ) <= {$args['annee']}
							AND c.toppersdrodevorsa = '1'
							AND ( o.rgorient IS NULL OR o.rgorient = 1 )
							{$filtre}
							{$bloc[0]} 
							AND enfants.id = f.id
					;";
					if( $bloc['enfant'] ) { // avec enfant(s)
						$sqlFound = $this->query( $blocSQLEnfant );
						$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
					}
					else { // sans enfant
						$sqlFound = $this->query( $blocSQLTotal );
						$total = $sqlFound[0][0]['count'];
						$sqlFound = $this->query( $blocSQLEnfant );
						$resultats[$keyRow][$keyCol] = $total - $sqlFound[0][0]['count'];
					}
				}
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
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droitet Devoirs.
				" AND o.typeorient_id IN ( {$this->isEmploi} )", //" AND (o.typeorient_id = 1 OR o.typeorient_id = 3) ",
				" AND o.typeorient_id IN ( {$this->isSocial} )", //" AND (o.typeorient_id = 2) ",
			 	" AND (o.statut_orient = 'En attente')"
			);
//			Cas 1 :
//				1205	Niveau Vbis: fin de scolarité obligatoire
//				1206	Niveau VI: pas de niveau
//				1207	Niveau VII: jamais scolarisé
//			Cas 2 :
//				1204	Niveau V: CAP/BEP
//			Cas 3 :
//				1203	Niveau IV: BAC ou équivalent
//			Cas 4 :
//				1201	Niveau I/II: enseignement supérieur
//				1202	Niveau III: BAC + 2
//			Cas 5 :
//				unknown
			$blocs = array(
				" AND ( dsps.nivetu IN ( '1205', '1206', '1207' ) ) ",
				" AND ( dsps.nivetu IN ( '1204' ) ) ",
				" AND ( dsps.nivetu IN ( '1203' ) ) ",
				" AND ( dsps.nivetu IN ( '1201', '1202') ) ",
				" AND ( dsps.nivetu IS NULL ) ",
			);
			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					$blocSQL = "
						SELECT count(DISTINCT p.id)    
						FROM
							personnes p,
							foyers f,
							dossiers d,
							calculsdroitsrsa c,
							orientsstructs o,
							dsps
						WHERE 
							p.id = c.personne_id
							AND p.foyer_id = f.id
							AND d.id = f.dossier_id
							AND p.id = o.personne_id
							AND p.id = dsps.personne_id
							AND ( EXTRACT ( YEAR FROM d.dtdemrsa ) ) <= {$args['annee']}
							AND c.toppersdrodevorsa = '1'
							AND ( o.rgorient IS NULL OR o.rgorient = 1 )
							{$filtre}
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
				}
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
			$resultats = array();

			$filtres = array(
				array("select" => false, "now"=>"", "before"=>""), // Seulement le champ Droit et Devoirs.
				array("select" => true, "now"=>$this->isSocial, "before"=>$this->isEmploi),
				array("select" => true, "now"=>$this->isEmploi, "before"=>$this->isSocial)
			);
			$blocs = array(
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '0' AND '24' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '25' AND '29' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '30' AND '39' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '40' AND '49' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '50' AND '59' ",
				" AND ( EXTRACT ( YEAR FROM AGE(timestamp '{$args['annee']}-12-31', p.dtnai ) ) ) BETWEEN '60' AND '999' ",
				" AND ( p.dtnai IS NULL) "
			);
			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					$select = "";
					$where = " AND o.rgorient > 1 ";

					if( $filtre['select'] ) {
						$select = $this->now_and_before;
						$where = " 
							AND o.rgorient = noworient.rgorient
							AND noworient.typeorient_id IN ( {$filtre['now']})
							AND beforeorient.typeorient_id IN ( {$filtre['before']} )
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
							{$where}
							{$bloc}
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
		}

		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'situation'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicReorientSituation($args) {
			$resultats = array();

			$filtres = array(
				array("select" => false, "now"=>"", "before"=>""), // Seulement le champ Droit et Devoirs.
				array("select" => true, "now"=>$this->isSocial, "before"=>$this->isEmploi),
				array("select" => true, "now"=>$this->isEmploi, "before"=>$this->isSocial)
			);

			$homme = " AND (p.sexe = '1') ";
			$femme = " AND (p.sexe = '2') ";
			$seul = " AND (f.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')) ";
			$couple = " AND (f.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')) ";
			$rsaMajore = " ";
//			$rsamajore'; true (bénéficiant du rsa majoré) ou false ( ne bénficiant pas)
//			#Model=detailscalculdroitrsa.natpf#
//			true :
//				"RSI" : RSA Socle majoré (Financement sur fonds Conseil général)
//				"RCI" : RSA Activité majoré (Financement sur fonds Etat)
//			false :
//				"RSD" : RSA Socle (Financement sur fonds Conseil général)
//				"RSU" : RSA Socle Etat Contrat aidé  (Financement sur fonds Etat)
//				"RSB" : RSA Socle Local (Financement sur fonds Conseil général)
//				"RCD" : RSA Activité (Financement sur fonds Etat)
//				"RCU" : RSA Activité Etat Contrat aidé (Financement sur fonds Etat)
//				"RCB" : RSA Activité Local (Financement sur fonds Conseil général)

			$blocs = array(
				array("{$homme}{$seul}", "enfant"=>false),
				array("{$femme}{$seul}", "enfant"=>false),
				array("{$homme}{$seul}", "enfant"=>true),
				array("{$homme}{$seul}{$rsaMajore}", "enfant"=>true),
				array("{$femme}{$seul}", "enfant"=>true),
				array("{$femme}{$seul}{$rsaMajore}", "enfant"=>true),
				array("{$homme}{$couple}", "enfant"=>false),
				array("{$femme}{$couple}", "enfant"=>false),
				array("{$homme}{$couple}", "enfant"=>true),
				array("{$femme}{$couple}", "enfant"=>true),
				// non connue :
				array(" AND ( (f.sitfam = 'ABA') OR ( f.sitfam IS NULL ) OR ( f.sitfam = '' ) )", "enfant"=>false)
			);
			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					$select = "";
					$where = " AND o.rgorient > 1 ";

					if( $filtre['select'] ) {
						$select = $this->now_and_before;
						$where = " 
							AND o.rgorient = noworient.rgorient
							AND noworient.typeorient_id IN ( {$filtre['now']})
							AND beforeorient.typeorient_id IN ( {$filtre['before']} )
						";
					}

					$blocSQLTotal = "
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
							{$bloc[0]}
					;";

					$blocSQLEnfant = "
						SELECT count(DISTINCT p.id)    
						FROM
							personnes p {$select},
							foyers f,
							dossiers d,
							calculsdroitsrsa c,
							orientsstructs o
							,
							( 
								SELECT 
									count(*), fo.id
								FROM 
									foyers fo
									INNER JOIN personnes pe ON pe.foyer_id = fo.id
									INNER JOIN prestations pr ON pr.personne_id = pe.id
									AND pr.natprest = 'RSA'
									AND pr.rolepers = 'ENF'
								GROUP BY fo.id 
							) enfants
						WHERE 
							p.id = c.personne_id
							AND p.foyer_id = f.id
							AND d.id = f.dossier_id
							AND p.id = o.personne_id
							AND ( EXTRACT ( YEAR FROM d.dtdemrsa ) ) <= {$args['annee']}
							AND c.toppersdrodevorsa = '1'
							AND o.rgorient > 1
							{$where}
							{$bloc[0]} 
							AND enfants.id = f.id
					;";
					if( $bloc['enfant'] ) { // avec enfant(s)
						$sqlFound = $this->query( $blocSQLEnfant );
						$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
					}
					else { // sans enfant
						$sqlFound = $this->query( $blocSQLTotal );
						$total = $sqlFound[0][0]['count'];
						$sqlFound = $this->query( $blocSQLEnfant );
						$resultats[$keyRow][$keyCol] = $total - $sqlFound[0][0]['count'];
					}
				}
			}
			return $resultats;
		}

		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'formation'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicReorientFormation($args) {
			$resultats = array();
			$filtres = array(
				array("select" => false, "now"=>"", "before"=>""), // Seulement le champ Droit et Devoirs.
				array("select" => true, "now"=>$this->isSocial, "before"=>$this->isEmploi),
				array("select" => true, "now"=>$this->isEmploi, "before"=>$this->isSocial)
			);
			$blocs = array(
				" AND ( dsps.nivetu IN ( '1205', '1206', '1207' ) ) ",
				" AND ( dsps.nivetu IN ( '1204' ) ) ",
				" AND ( dsps.nivetu IN ( '1203' ) ) ",
				" AND ( dsps.nivetu IN ( '1201', '1202') ) ",
				" AND ( dsps.nivetu IS NULL ) ",
			);
			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					$select = "";
					$where = " AND o.rgorient > 1 ";

					if( $filtre['select'] ) {
						$select = $this->now_and_before;
						$where = "
							AND o.rgorient = noworient.rgorient
							AND noworient.typeorient_id IN ( {$filtre['now']})
							AND beforeorient.typeorient_id IN ( {$filtre['before']} )
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
							dsps
						WHERE 
							p.id = c.personne_id
							AND p.foyer_id = f.id
							AND d.id = f.dossier_id
							AND p.id = o.personne_id
							AND p.id = dsps.personne_id
							AND ( EXTRACT ( YEAR FROM d.dtdemrsa ) ) <= {$args['annee']}
							AND c.toppersdrodevorsa = '1'
							{$where}
							{$bloc}
					;";

					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
		}

		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'anciennete'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		protected function _indicReorientAnciennete($args) {
			$resultats = array();
			$filtres = array(
				array("select" => false, "now"=>"", "before"=>""), // Seulement le champ Droit et Devoirs.
				array("select" => true, "now"=>$this->isSocial, "before"=>$this->isEmploi),
				array("select" => true, "now"=>$this->isEmploi, "before"=>$this->isSocial)
			);
			$blocs = array(
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' ) ",
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '6' MONTH ) ",
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '1' YEAR ) ",
				" AND d.dtdemrsa BETWEEN ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR - INTERVAL '1' DAY ) AND ( timestamp '{$args['annee']}-12-31' - INTERVAL '2' YEAR ) ",
				" AND d.dtdemrsa < ( timestamp '{$args['annee']}-12-31' - INTERVAL '5' YEAR ) ",
				" AND ( d.dtdemrsa IS NULL) "
			);
			foreach( $blocs as $keyRow => $bloc) {
				foreach( $filtres as $keyCol => $filtre) {
					$select = "";
					$where = " AND o.rgorient > 1 ";

					if( $filtre['select'] ) {
						$select = $this->now_and_before;
						$where = " 
							AND o.rgorient = noworient.rgorient
							AND noworient.typeorient_id IN ( {$filtre['now']})
							AND beforeorient.typeorient_id IN ( {$filtre['before']} )
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
							{$where}
							{$bloc}
					;";

					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
				}
			}
			return $resultats;
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