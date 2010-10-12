<?php
	App::import( 'Sanitize' );

	class Statistiquesministerielle extends AppModel
	{
		var $name = 'Statistiqueministerielle';
		var $useTable = false;

		
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
		function indicateursOrientations($args)
		{ 	
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
		function _indicOrientAge($args)
		{
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droitet Devoirs.
				" AND (o.typeorient_id = 1 OR o.typeorient_id = 3) ",
				" AND (o.typeorient_id = 2) ",
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
		function _indicOrientAnciennete($args)
		{
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droitet Devoirs.
				" AND (o.typeorient_id = 1 OR o.typeorient_id = 3) ",
				" AND (o.typeorient_id = 2) ",
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
		function _indicOrientSituation($args)
		{
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droit et Devoirs.
				" AND (o.typeorient_id = 1 OR o.typeorient_id = 3) ",
				" AND (o.typeorient_id = 2) ",
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
				array(" AND (f.sitfam = 'ABA') ", "enfant"=>false)
			);
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
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
							{$filtre}
							{$bloc[0]} 
							AND enfants.id = f.id
					;";
					if( $bloc['enfant'] )
					{ // avec enfant(s)
						$sqlFound = $this->query( $blocSQLEnfant );
						$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
					}
					else
					{ // sans enfant
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
		function _indicOrientFormation($args)
		{
			$resultats = array();
			$filtres = array(
				"", // Seulement le champ Droitet Devoirs.
				" AND (o.typeorient_id = 1 OR o.typeorient_id = 3) ",
				" AND (o.typeorient_id = 2) ",
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
		function indicateursCaracteristiquesContrats($args)
		{ 	
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
		
		function _indicCaraContratsR1Total($args)
		{
			return array( "Non géré");
		}
		
		function _indicCaraContratsR1DD($args)
		{
			return array( "Non géré", "Non géré");
		}		

		function _indicCaraContratsR2Total($args)
		{
			return array( "Non géré");
		}		
		
		function _indicCaraContratsR2DD($args)
		{
			return array( "Non géré", "Non géré");
		}
				
		function _indicCaraContratsR3Total($args)
		{
			$blocs = array(
				" ",
				null,
				" AND sr.typeorient_id = 3 ",
				" AND sr.typeorient_id in (1, 2) ",
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
		
		function _indicCaraContratsR3DD($args)
		{
			$filtres = array(
				" AND cdr.toppersdrodevorsa = '1' ",
				" AND cdr.toppersdrodevorsa = '0' "
			);
			$blocs = array(
				" ",
				null,
				" AND sr.typeorient_id = 3 ",
				" AND sr.typeorient_id in (1, 2) ",
			);
	
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
					if( is_null($bloc) )
					{
						$resultats[$keyRow][$keyCol] = "Non géré";
					}
					else
					{
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

		function _indicCaraContratsR4($args)
		{
			$blocs = array(
				" AND ci.duree_engag < 6 ",
				" AND ci.duree_engag >= 6 AND ci.duree_engag < 12 ",
				" AND ci.duree_engag >= 12 "
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
							AND sr.typeorient_id = 3 
							{$bloc}	
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}				
			}
			return $resultats;	
		}
		
		function _indicCaraContratsR5($args)
		{
			$blocs = array(
				" AND ci.duree_engag < 6 ",
				" AND ci.duree_engag >= 6 AND ci.duree_engag < 12 ",
				" AND ci.duree_engag >= 12 "
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
							AND sr.typeorient_id IN ( 1, 2) 
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
		function indicateursNatureContrats($args)
		{ 	
			set_time_limit(0);
			$filtres = array(
				" AND engag_object IN ( '29', '41','42', '43', '45', '46', '48') ", // a01 Activités, stages ou formation destinés à acquérir des compétences professionnelles
				" AND engag_object IN ( '51') ", // a02 Orientation vers le service public de l'emploi, parcours de recherche d'emploi
				"  ", // a03 Mesures d'insertion par l'activité économique (IAE)
				" AND engag_object IN ( '55' ) ", // a04 Aide à la réalisation d’un projet de création, de reprise ou de poursuite d’une activité non salariée
				" AND engag_object IN ( '52', '53', '54', '56') ", // a05 Emploi aidé
				" AND engag_object IN ( '57', '58', '59') ", // a06 Emploi non aidé
				" AND engag_object IN ( '26') ", // a07 Actions facilitant le lien social (développement de l'autonomie sociale, activités collectives,…)
				" AND engag_object IN ( '44' ) ", // a08 Actions facilitant la mobilité (permis de conduire, acquisition / location de véhicule, frais de transport…)
				" AND engag_object IN ( '05', '06', '31', '33') ", // a09 Actions visant l'accès à un logement, relogement ou à l'amélioration de l'habitat
				" AND engag_object IN ( '21') ", // a10 Actions facilitant l'accès aux soins
				" AND engag_object IN ( '07') ", // a11 Actions visant l'autonomie financière (constitution d'un dossier de surendettement,...)
				" AND engag_object IN ( '1P', '1F', '02', '03', '04') ", // a12 Actions visant la famille et la parentalité (soutien familiale, garde d'enfant, …)
				" AND engag_object IN ( '22') ", // a13 Lutte contre l'illettrisme ; acquisition des savoirs de base
				" AND engag_object IN ( '10', '23', '24') "  // a14 Autres actions
			);
			$blocs = array(
				"L262-35" => " AND sr.typeorient_id = 3 ",
				"L262-36" => " AND sr.typeorient_id in (1, 2) "
			);
	
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
					if( is_null($bloc) )
					{
						$resultats[$keyRow][$keyCol] = "Non géré";
					}
					else
					{
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
		function indicateursDelais($args)
		{ 
			set_time_limit(0);
			$resultats = array();
			$resultats['Amoy'] = $this->_indicDelaisAmoy($args);
			$resultats['Bmoy'] = $this->_indicDelaisBmoy($args);
			$resultats['Btot'] = $this->_indicDelaisBtot($args);
			return $resultats;
		}

		function _indicDelaisAmoy($args)
		{	
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
		
		function _indicDelaisBmoy($args)
		{
			$blocs = array(
				" ",
				null,
				" AND os.typeorient_id = 3 ",
				" AND os.typeorient_id in (1, 2) ",
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

		function _indicDelaisBtot($args)
		{
			set_time_limit(0);
			$filtres = array(
				" ",
				" AND ( ci.date_saisi_ci - os.date_valid ) <= '60' ",
				" AND ( ci.date_saisi_ci - os.date_valid ) BETWEEN '61' AND '120' ",
				" AND ( ci.date_saisi_ci - os.date_valid ) >= '121' "
			);
			$blocs = array(
				null,
				"L262-35" => " AND os.typeorient_id = 3 ",
				"L262-36" => " AND os.typeorient_id in (1, 2) "
			);
	
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
					if( is_null($bloc) )
					{
						$resultats[$keyRow][$keyCol] = "Non géré";
					}
					else
					{
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
		
		function indicateursOrganismes($args)
		{
			// Nombre de personnes dans le champ des Droits et Devoirs (L262-28) au 31 décembre de l'année
			// ET qui ont un référent.
			$sql = 'SELECT count(*), typesorients.id, typesorients.lib_type_orient
						FROM personnes
						LEFT JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
						LEFT JOIN personnes_referents ON (personnes_referents.personne_id = personnes.id)
						LEFT JOIN structuresreferentes ON ( structuresreferentes.id = personnes_referents.structurereferente_id )
						LEFT JOIN typesorients ON ( typesorients.id = structuresreferentes.typeorient_id  )
						WHERE calculsdroitsrsa.toppersdrodevorsa = \'1\'
						GROUP BY typesorients.id, typesorients.lib_type_orient
						ORDER BY typesorients.id;';
			$sqlFound = $this->query( $sql );
			$results = array();
			$results['DroitsEtDevoirs'] = 0; 
			$results['Autres'] = 0;
			foreach($sqlFound as $row)
			{
				if( empty($row[0]['id']) ) continue;
				$results['DroitsEtDevoirs'] += $row[0]['count'];
				switch( $row[0]['id']){
					case 1: //Socioprofessionnelle
						$results['SP'] = $row[0]['count'];//SP : Socio Professionelle
						break;
					case 2: //Social
						$results['SSD'] = $row[0]['count'];//SSD : Service Social du Département
						break;
					case 3: //Emploi
						$results['PE'] = $row[0]['count'];
						break;
					default: // Autres
						$results['Autres'] += $row[0]['count'];	
				}
			}
			// 
//			$sql = '';
//			$result[''] = $this->query( $sql );
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
		function indicateursReorientations($args)
		{ 	
			set_time_limit(0);
			$resultats = array();
//			$resultats['age'] = $this->_indicReorientAge($args);
//			$resultats['situation'] = $this->_indicrReorientSituation($args);
//			$resultats['formation'] = $this->_indicReorientFormation($args);
//			$resultats['anciennete'] = $this->_indicReorientAnciennete($args);
			return $resultats;
		}
		
		/**
		 * Calcul des indicateurs de réorientation pour le bloc 'age'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */		
		function _indicReorientAge($args)
		{
			$resultats = array();
			$filtres = array(
				"  ",
				"  ",
			 	"  "
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
						SELECT count(pe.id)     
						FROM
							personnes p
						WHERE 
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
		 * Calcul des indicateurs de réorientation pour le bloc 'situation'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */		
		function _indicReorientSituation($args)
		{
			$resultats = array();
			$filtres = array(
				"  ",
				"  ",
			 	"  "
			);
			$homme = " AND (p.sexe = '1') ";
			$femme = " AND (p.sexe = '2') ";
			$seul = " AND (f.sitfam IN ('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU')) ";
			$couple = " AND (f.sitfam IN ('MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'VIM')) ";
			$rsaMajore = " ";
			
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
				array(" AND (f.sitfam = 'ABA') ", "enfant"=>false)
			);
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
					$blocSQLTotal = "
						SELECT count(pe.id)     
						FROM
							personnes p
						WHERE
							{$filtre}
							{$bloc[0]}
					;";
					$blocSQLEnfant = "
						SELECT count(pe.id)     
						FROM
							personnes p
						WHERE
							{$filtre}
							{$bloc[0]} 
					;";
					if( $bloc['enfant'] )
					{ // avec enfant(s)
						$sqlFound = $this->query( $blocSQLEnfant );
						$resultats[$keyRow][$keyCol] = $sqlFound[0][0]['count'];
					}
					else
					{ // sans enfant
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
		function _indicReorientFormation($args)
		{
			$resultats = array();
			$filtres = array(
				"  ",
				"  ",
			 	"  "
			);
			$blocs = array(
				" AND ( dsps.nivetu IN ( '1205', '1206', '1207' ) ) ",
				" AND ( dsps.nivetu IN ( '1204' ) ) ",
				" AND ( dsps.nivetu IN ( '1203' ) ) ",
				" AND ( dsps.nivetu IN ( '1201', '1202') ) ",
				" AND ( dsps.nivetu IS NULL ) ",
			);
			foreach( $blocs as $keyRow => $bloc)
			{
				foreach( $filtres as $keyCol => $filtre)
				{
					$blocSQL = "
						SELECT count(pe.id)     
						FROM
							personnes p
						WHERE
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
		 * Calcul des indicateurs de réorientation pour le bloc 'anciennete'.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */		
		function _indicReorientAnciennete($args)
		{
			$resultats = array();
			$filtres = array(
				"  ",
				"  ",
			 	"  "
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
						SELECT count(pe.id)     
						FROM
							personnes p
						WHERE
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
// INDICATEURS MOTIFS DE RÉORIENTAITONS :		
//		
//##############################################################################		

		/**
		 * Calcul des indicateurs motifs de réorientations.
		 * @param $args array Selon provenant du formulaire.
		 * $args contient les clés : 'localisation', 'service', 'annee'.
		 * @author Thierry Nemes - Adullact projet
		 */
		function indicateursMotifsReorientations($args)
		{ 	
			set_time_limit(0);
			$resultats = array();
//			$resultats['tab1'] = $this->_indicMotifsReorientTab1($args);
//			$resultats['tab2'] = $this->_indicMotifsReorientTab2($args);
			return $resultats;
		}		
		
		function _indicMotifsReorientTab1($args)
		{
			$blocs = array(
				null,
				null,
				null,
				null
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
						SELECT count(pe.id)     
						FROM
							personnes p
						WHERE
							{$bloc}	
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}				
			}
			return $resultats;	
		}		
		
		function _indicMotifsReorientTab2($args)
		{
			$blocs = array(
				null,
				null,
				null
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
						SELECT count(pe.id)     
						FROM
							personnes p
						WHERE
							{$bloc}	
					;";
					$sqlFound = $this->query( $blocSQL );
					$resultats[$keyRow] = $sqlFound[0][0]['count'];
				}				
			}
			return $resultats;	
		}

		
	}
?>