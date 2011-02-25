<?php
	/**
	*
	*/

    class DevDoublonsShell extends AppShell
    {
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => true,
			'limit' => 10,
			'count' => true,
		);

		public $verbose;
		public $count = true;

		/**
		*
		*/

		public function initialize() {
			parent::initialize();

			$this->Personne = ClassRegistry::init( 'Personne' );

			$this->count = $this->_getNamedValue( 'count', 'boolean' );
			$this->limit = $this->_getNamedValue( 'limit', 'integer' );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$database = $this->Personne->getDataSource( $this->Personne->useDbConfig )->config['database'];

			$this->out();
			$this->out( "Shell de vérification de doublons, {$database}" );
			$this->out();
			$this->hr();
		}

		/**
		*
		*/

		public $_traductions = array(
			'personnes_count' => 'comptage du nombre total de personnes en base',
			'personnes_demcjtrsa_count' => 'comptage du nombre total de personnes demandeur ou conjoint RSA',
			'personnes_nondemcjtrsa_count' => 'comptage du nombre total de personnes non-demandeur et non-conjoint RSA',
			'personnes_sansprestationrsa_count' => 'comptage du nombre de personnes sans prestation pour le RSA',
			'personnes_plusieursprestationrsa_count' => 'comptage du nombre de personnes ayant plusieurs prestations pour le RSA',

			'demcjtdoublonsmemefoyer' => 'demandeurs et conjoint en doublons dans le même foyer',
			'nondemcjtdoublonsmemefoyer' => 'doublons dans les non-demandeurs et non-conjoints dans le même foyer',
			'demcjtdoublonsfoyerdiffouverts' => 'demandeurs et conjoints en doublons dans des foyers différents dont les droits sont ouverts',
			'nondemcjtdoublonsfoyerdiffouverts' => 'doublons dans les non-demandeurs et non-conjoints dans des foyers différents dont les droits sont ouverts',

			'nirdtnai' => 'nir correct, même nir et même date de naissance',
			'npdtnai' => 'même nom, même prénom, même date de naissance',
			'noponnedtnaiergnai' => 'même nom ou prénom ou nom de naissance et même date de naissance et même rang de naissance',

			'total' => 'total des doublons trouvés',
			'demcjt' => 'sur les demandeurs et conjoints',
			'nondemcjt' => 'sur les non-demandeurs et non-conjoints',
		);

		/**
		* Explications :
			- personnes_count => comptage du nombre total de personnes en base
			- personnes_demcjtrsa_count => comptage du nombre total de personnes demandeur ou conjoint RSA
			- personnes_nondemcjtrsa_count => comptage du nombre total de personnes non-demandeur et non-conjoint RSA
			- personnes_sansprestationrsa_count => comptage du nombre de personnes sans prestation pour le RSA
			- personnes_plusieursprestationrsa_count => comptage du nombre de personnes ayant plusieurs prestations pour le RSA

			- demcjtdoublonsmemefoyer => demandeurs et conjoint en doublons dans le même foyer
			- nondemcjtdoublonsmemefoyer => doublons dans les non-demandeurs et non-conjoints dans le même foyer
			- demcjtdoublonsfoyerdiffouverts => demandeurs et conjoints en doublons dans des foyers différents dont les droits sont ouverts
			- nondemcjtdoublonsfoyerdiffouverts => doublons dans les non-demandeurs et non-conjoints dans des foyers différents dont les droits sont ouverts

			- nirdtnai => nir correct, même nir et même date de naissance
			- npdtnai => même nom, même prénom, même date de naissance
			- noponnedtnaiergnai => même nom ou prénom ou nom de naissance et même date de naissance et même rang de naissance

			- total_demcjt => total des doublons sur les demandeurs et conjoints
			- total_nondemcjt => total des doublons sur les non-demandeurs et non-conjoints
			- total => total des doublons trouvés
		*/

		public function main() {
			$body = '';

			// -----------------------------------------------------------------
			// Initialisation
			// -----------------------------------------------------------------

			if( $this->count ) {
				$counts = array();
				$countQueries = array(
					'personnes_count' => 'SELECT COUNT(DISTINCT(personnes.id)) AS count FROM personnes;',
					'personnes_demcjtrsa_count' => 'SELECT
															COUNT(DISTINCT(personnes.id)) AS count
														FROM personnes
															INNER JOIN prestations ON (
																personnes.id = prestations.personne_id
																AND prestations.natprest = \'RSA\'
															)
														WHERE prestations.rolepers IN ( \'DEM\', \'CJT\' );',
					'personnes_nondemcjtrsa_count' => 'SELECT
																COUNT(DISTINCT(personnes.id))
															FROM personnes
																INNER JOIN prestations ON (
																	personnes.id = prestations.personne_id
																	AND prestations.natprest = \'RSA\'
																)
															WHERE prestations.rolepers NOT IN ( \'DEM\', \'CJT\' );',
					'personnes_sansprestationrsa_count' => 'SELECT
																	COUNT(DISTINCT(personnes.id))
																FROM personnes
																WHERE (
																	SELECT
																			COUNT(prestations.*)
																		FROM prestations
																		WHERE prestations.personne_id = personnes.id
																			AND prestations.natprest = \'RSA\'
																) = 0;',
					'personnes_plusieursprestationrsa_count' => 'SELECT
																	COUNT(DISTINCT(personnes.id))
																FROM personnes
																WHERE (
																	SELECT
																			COUNT(prestations.*)
																		FROM prestations
																		WHERE prestations.personne_id = personnes.id
																			AND prestations.natprest = \'RSA\'
																) > 1;'
				);

				foreach( $countQueries as $key => $countQuery ) {
					$results = $this->Personne->query( $countQuery );
					$counts[$key] = Set::classicExtract( $results, '0.0.count' );
				}

				if( !empty( $counts ) ) {
					$body .= "<table><tbody>";
					foreach( $counts as $k => $count ) {
						$body .= "<tr><th>".$this->_traductions[$k]."</th><td class=\"number\">{$count}</td></tr>";
					}
					$body .= "</tbody></table>";
				}

				debug( $counts );
			}

			// Doublons personnes
			$conditions = array(
				'nirdtnai' => '( nir_correct( p1.nir ) AND p1.nir = p2.nir AND p1.dtnai = p2.dtnai )',
				'npdtnai' => '(
								TRIM( BOTH \' \' FROM p1.nom ) = TRIM( BOTH \' \' FROM p2.nom )
								AND TRIM( BOTH \' \' FROM p1.prenom ) = TRIM( BOTH \' \' FROM p2.prenom )
								AND p1.dtnai = p2.dtnai
							)',
				// FIXME: faire une reqûete pour trouver les hommes ayant un nom et un nomnai <>
				// FIXME: il existe, dans le même foyer des personnes qui n'ont que la dtnai, dans le même foyers
				'noponnedtnaiergnai' => '(
											(
												TRIM( BOTH \' \' FROM p1.nom ) <> TRIM( BOTH \' \' FROM p2.nom )
												AND TRIM( BOTH \' \' FROM p1.prenom ) = TRIM( BOTH \' \' FROM p2.prenom )
												AND TRIM( BOTH \' \' FROM p1.nomnai ) = TRIM( BOTH \' \' FROM p2.nomnai )
											)
											OR (
												TRIM( BOTH \' \' FROM p1.nom ) = TRIM( BOTH \' \' FROM p2.nom )
												AND TRIM( BOTH \' \' FROM p1.prenom ) <> TRIM( BOTH \' \' FROM p2.prenom )
												AND TRIM( BOTH \' \' FROM p1.nomnai ) = TRIM( BOTH \' \' FROM p2.nomnai )
											)
											OR (
												TRIM( BOTH \' \' FROM p1.nom ) = TRIM( BOTH \' \' FROM p2.nom )
												AND TRIM( BOTH \' \' FROM p1.prenom ) = TRIM( BOTH \' \' FROM p2.prenom )
												AND TRIM( BOTH \' \' FROM p1.nomnai ) <> TRIM( BOTH \' \' FROM p2.nomnai )
											)
										)
										AND p1.dtnai = p2.dtnai
										AND (
											( p1.rgnai IS NULL AND p2.rgnai IS NULL )
											OR ( p1.rgnai IS NOT NULL AND p2.rgnai IS NOT NULL AND p1.rgnai = p2.rgnai )
										)',
			);

			$fields = ( $this->count ? 'COUNT(*)' : 'p1.id AS "P1__id", p1.nir AS "P1__nir", p1.dtnai AS "P1__dtnai", p1.foyer_id AS "P1__foyer_id", p2.id AS "P2__id", p2.nir AS "P2__nir", p2.dtnai AS "P2__dtnai", p2.foyer_id AS "P2__foyer_id"' );

			$countsDoublonsPersonnes = array();
			$countQueries = array(
				'demcjtdoublonsmemefoyer' => 'SELECT
														'.$fields.'
													FROM personnes p1, personnes p2
													WHERE
														p1.id < p2.id
														AND p1.foyer_id = p2.foyer_id
														AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p1.id AND prestations.rolepers IN ( \'DEM\', \'CJT\' ) ) > 0
														AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p2.id AND prestations.rolepers IN ( \'DEM\', \'CJT\' ) ) > 0',
				'nondemcjtdoublonsmemefoyer' => 'SELECT
														'.$fields.'
													FROM personnes p1, personnes p2
													WHERE
														p1.id < p2.id
														AND p1.foyer_id = p2.foyer_id
														AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p1.id AND prestations.rolepers NOT IN ( \'DEM\', \'CJT\' ) ) > 0
														AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p2.id AND prestations.rolepers NOT IN ( \'DEM\', \'CJT\' ) ) > 0',
				'demcjtdoublonsfoyerdiffouverts' => 'SELECT
														'.$fields.'
													FROM
														personnes p1
															INNER JOIN foyers AS f1 ON p1.foyer_id = f1.id
															INNER JOIN situationsdossiersrsa AS s1 ON ( f1.dossier_id = s1.dossier_id ),
														personnes p2
															INNER JOIN foyers AS f2 ON p2.foyer_id = f2.id
															INNER JOIN situationsdossiersrsa AS s2 ON ( f2.dossier_id = s2.dossier_id )
													WHERE
														p1.id < p2.id
														AND p1.foyer_id <> p2.foyer_id
														AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p1.id AND prestations.rolepers IN ( \'DEM\', \'CJT\' ) ) > 0
														AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p2.id AND prestations.rolepers IN ( \'DEM\', \'CJT\' ) ) > 0
														AND s1.etatdosrsa IN ( \'Z\', \'2\', \'3\', \'4\' )
														AND s2.etatdosrsa IN ( \'Z\', \'2\', \'3\', \'4\' )',
				'nondemcjtdoublonsfoyerdiffouverts' => 'SELECT
															'.$fields.'
														FROM
															personnes p1
																INNER JOIN foyers AS f1 ON p1.foyer_id = f1.id
																INNER JOIN situationsdossiersrsa AS s1 ON ( f1.dossier_id = s1.dossier_id ),
															personnes p2
																INNER JOIN foyers AS f2 ON p2.foyer_id = f2.id
																INNER JOIN situationsdossiersrsa AS s2 ON ( f2.dossier_id = s2.dossier_id )
														WHERE
															p1.id < p2.id
															AND p1.foyer_id <> p2.foyer_id
															AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p1.id AND prestations.rolepers NOT IN ( \'DEM\', \'CJT\' ) ) > 0
															AND ( SELECT COUNT(*) FROM prestations WHERE prestations.natprest = \'RSA\' AND prestations.personne_id = p2.id AND prestations.rolepers NOT IN ( \'DEM\', \'CJT\' ) ) > 0
															AND s1.etatdosrsa IN ( \'Z\', \'2\', \'3\', \'4\' )
															AND s2.etatdosrsa IN ( \'Z\', \'2\', \'3\', \'4\' )',
			);

			foreach( $countQueries as $keyQuery => $countQuery ) {
				foreach( $conditions as $keyCondition => $condition ) {
					$sql = $countQuery.' AND '.$condition;

					$innerConditions = $conditions;
					unset( $innerConditions[$keyCondition] );
					if( !empty( $innerConditions ) ) {
						foreach( $innerConditions as $innerCondition ) {
							$sql .= ' AND NOT '.$innerCondition;
						}
					}

					if( !$this->count ) {
						$sql .= " LIMIT {$this->limit}";
					}

					$results = $this->Personne->query( $sql );

					if( $this->count ) {
						$countsDoublonsPersonnes["{$keyQuery}_$keyCondition"] = Set::classicExtract( $results, '0.0.count' );
					}
					else {
						$countsDoublonsPersonnes["{$keyQuery}_$keyCondition"] = $results;
					}
				}
			}

			// Calcul du total
			if( $this->count ) {
				$total = 0;
				$totalDemCjt = 0;
				$totalNonDemCjt = 0;
				foreach( $countsDoublonsPersonnes as $i => $nb ) {
					$total += $nb;
					if( preg_match( '/^demcjt/', $i ) ) {
						$totalDemCjt += $nb;
					}
					else {
						$totalNonDemCjt += $nb;
					}
				}
				$countsDoublonsPersonnes['total_demcjt'] = $totalDemCjt;
				$countsDoublonsPersonnes['total_nondemcjt'] = $totalNonDemCjt;
				$countsDoublonsPersonnes['total'] = $total;

				//
				$body .= "<table><tbody>";
				foreach( $countsDoublonsPersonnes as $k => $count ) {
					if( strstr( $k, '_' ) !== false ) {
						list( $l, $m ) = explode( '_', $k );
						$traduction = $this->_traductions[$l]." ".$this->_traductions[$m];
					}
					else {
						$traduction = $this->_traductions[$k];
					}

					$body .= "<tr><th>".$traduction."</th><td class=\"number\">{$count}</td></tr>";
				}
				$body .= "</tbody></table>";
			}
			else {
				foreach( $countsDoublonsPersonnes as $k => $details ) {
					if( strstr( '_', $k ) !== false ) {
						list( $l, $m ) = explode( '_', $k );
						$traduction = $this->_traductions[$l]." ".$this->_traductions[$m];
					}
					else {
						$traduction = $this->_traductions[$k];
					}

					if( empty( $traduction ) ) {
						$traduction = $k;
					}

					$body .= "<h1>{$traduction}</h1><table><tbody>";
					foreach( $details as $detail ) {
						foreach( array( 'id', 'nir', 'dtnai', 'foyer_id' ) AS $field ) {
							$body .= "<tr><th>{$field}</th><td>{$detail['P1'][$field]}</td><td>{$detail['P2'][$field]}</td></tr>";
						}
					}
					$body .= "</tbody></table>";
				}
			}

			debug( $countsDoublonsPersonnes );

			// -----------------------------------------------------------------
			// Écriture du fichier HTML
			// -----------------------------------------------------------------
			$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
					"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
						<head>
							<title></title>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<style type="text/css" media="all">
								body { font-size: 12px; }
								table { border-collapse: collapse; margin: 0.5em 0; }
								thead, tbody { border: 3px solid black; }
								th, td { border: 1px solid black; padding: 0.125em 0.25em; text-align: left; }
								tr.odd { background: #eee; }
								td.number { text-align: right; }
							</style>
						</head><body>'.$body.'</body></html>';

			file_put_contents( 'dev_doublons.html', $html );
		}

		/**
		* Aide
		*/

		public function help() {
			$this->out( "Usage: cake/console/cake {$this->script} <commande> <paramètres>" );
			$this->hr();
			$this->out();
			$this->out('Paramètres:');
			$this->out( "\t-count <booléén>\n\t\tDoit-on faire le comptage (true) ou lister un échantillon (false) ?\n\t\tPar défaut: ".$this->_defaultToString( 'count' )."\n" );
			$this->out( "\t-limit <entier>\n\t\tNombre d'enregistrements de l'échantillon\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n" );
			$this->out();

			$this->_stop( 0 );
		}
    }
?>