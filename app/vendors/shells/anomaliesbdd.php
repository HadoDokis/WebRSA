<?php
    @ini_set( 'memory_limit', '2048M' );

	/**
	* INFO:
	*	- base de données d'environ 200 Mo, datée du 04/11/2009
	*		* 103664 dossiers
	*		* 282952 personnes
	*		=> script "full": environ 16 heures (57.430,52 secondes), fichier en sortie de 75,5 Mo
	*			+ Dossiers sans personne: 121 résultats
	*			+ Dossiers contenant des personnes mais sans demandeur RSA: 207 résultats
	*			+ Personnes avec NIR en doublon dans des foyers différents: 39986 personnes à traiter
	*			+ Personnes avec NIR en doublon dans le même foyer: 83157 personnes à traiter
	*			+ Personnes avec au moins un NIR manquant en doublon dans des foyers différents: 511 personnes à traiter
	*			+ Personnes avec au moins un NIR manquant en doublon dans le même foyer: 32 personnes à traiter
	*			+ Recherche des personnes demandeurs ou conjoints multiples au sein d'un foyer: 14444 personnes à traiter
	*
	*/

	/*
		SELECT *
			FROM personnes
				INNER JOIN prestations
					ON prestations.personne_id = personnes.id
						AND prestations.natprest = 'RSA'
						AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' )
			WHERE
				personnes.nir IS NOT NULL
				AND TRIM( BOTH ' ' FROM personnes.nir ) <> ''
				AND LENGTH( TRIM( BOTH ' ' FROM personnes.nir ) ) <> 15
			ORDER BY personnes.nir ASC
			LIMIT 100;
	*/

	// count -> 2966 (foyers avec des personnes ayant un nir erroné)
	/*
		SELECT DISTINCT( personnes.foyer_id )
			FROM personnes
				INNER JOIN prestations
					ON prestations.personne_id = personnes.id
						AND prestations.natprest = 'RSA'
						AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' )
			WHERE
				personnes.nir IS NOT NULL
				AND TRIM( BOTH ' ' FROM personnes.nir ) <> ''
				AND LENGTH( TRIM( BOTH ' ' FROM personnes.nir ) ) <> 15;
	*/

	//
	/* count -> 6841 (foyers à demandeurs multiples)
		SELECT DISTINCT( p1.foyer_id )
			FROM personnes AS p1,
				personnes AS p2,
				prestations AS pr1,
				prestations AS pr2
			WHERE p1.foyer_id = p2.foyer_id
				AND p1.id < p2.id
				AND pr1.personne_id = p1.id
				AND pr2.personne_id = p2.id
				AND pr1.natprest = 'RSA'
				AND pr2.natprest = 'RSA'
				AND pr1.rolepers = pr2.rolepers
				AND ( pr1.rolepers = 'DEM' OR pr1.rolepers = 'CJT' )
	*/

	// intersection -> 1107

	/*
	*/

    class AnomaliesbddShell extends Shell
    {
        var $uses = array( 'Personne' );
		var $output = '';
		var $outfile = null;
		var $Html = null;
		var $fields = array(
			'Foyer.Dossier.numdemrsa',
			'Personne.id',
			'Personne.nir',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.nomnai',
			'Personne.dtnai',
			'Prestation.rolepers',
		);
		var $headers = array(
			'N° demande',
			'id',
			'NIR',
			'Qual',
			'Nom',
			'Prénom',
			'Nomnai',
			'Dtnai',
			'Rolepers',
			'# personnes',
			'# contrats',
			'# orientstructs',
			'# RDV',
			'# DSP',
			'Titre séjour',
			'# grossesses',
			'# activités',
			'# avis PCG personnes',
		);

		var $limit = '';
		var $pageTitle = 'Rapport sur les anomalies des données de la base de données du %s';
		var $script = null;

		/// Paramètres
		var $type = 'short';
		var $dossiersvides = true;
		var $dossierssansdemandeur = true;
		var $nirsdoublonsfoyersdiff = true;
		var $nirsdoublonsmemefoyer = true;
		var $personnesdoublonsfoyersdiff = true;
		var $personnesdoublonsmemefoyer = true;
		var $demcjtmultiples = true;

		/// Aide sur les paramètres
		var $help = array(
			'type' => "Type de rapport (soit full, soit short).\n\tshort: permet de comptabiliser les différentes erreurs\n\tfull:  permet de lister toutes les entrées posant problème",
			'dossiersvides' => 'Dossiers ne contenant aucune personne liée',
			'dossierssansdemandeur' => 'Dossiers contenant des personnes, mais sans demandeur RSA',
			'nirsdoublonsfoyersdiff' => 'Personnes avec NIR en doublon dans des foyers différents',
			'nirsdoublonsmemefoyer' => 'Personnes avec NIR en doublon dans le même foyer',
			'personnesdoublonsfoyersdiff' => 'Personnes avec au moins un NIR manquant en doublon dans des foyers différents',
			'personnesdoublonsmemefoyer' => 'Personnes avec au moins un NIR manquant en doublon dans le même foyer',
			'demcjtmultiples' => 'Personnes demandeurs ou conjoints multiples au sein d\'un foyer'
		);

		/// Paramètres
		var $possibleParams = array(
			'type' => array( 'short', 'full' ),
			'dossiersvides' => array( 'true', 'false' ),
			'dossierssansdemandeur' => array( 'true', 'false' ),
			'nirsdoublonsfoyersdiff' => array( 'true', 'false' ),
			'nirsdoublonsmemefoyer' => array( 'true', 'false' ),
			'personnesdoublonsfoyersdiff' => array( 'true', 'false' ),
			'personnesdoublonsmemefoyer' => array( 'true', 'false' ),
			'demcjtmultiples' => array( 'true', 'false' )
		);

		/**
		* Affiche l'aide liée à un paramètre
		* @access protected
		*/

		function _printHelpParam( $param ) {
			$message = $this->help[$param];
			$this->out( "-{$param}" );
			$this->out( "\t{$message}" );
			$defaultValue = $this->{$param};
			$params[] = '-'.$param.' '.( is_bool( $defaultValue ) ? ( $defaultValue ? 'true' : 'false' ) : $defaultValue );
		}

		/**
		* Affiche l'aide liée au script (les paramètes possibles)
		* @access protected
		*/

		function _printHelp() {
			$this->out( "Paramètres possibles pour le script {$this->script}:" );
			$this->hr();
			$params = array();
			foreach( $this->help as $param => $message ) {
				$this->_printHelpParam( $param );
				$defaultValue = $this->{$param};
				$params[] = '-'.$param.' '.( is_bool( $defaultValue ) ? ( $defaultValue ? 'true' : 'false' ) : $defaultValue );
			}
			$this->hr();
			$this->out( sprintf( "Exemple (avec les valeurs par défaut): cake/console/cake %s %s", $this->script, implode( ' ', $params ) ) );
			$this->hr();
			exit( 0 );
		}

		/**
		*
		*/

        function startup() {
			$this->script = strtolower( preg_replace( '/Shell$/', '', $this->name ) );

			/// Demande d'aide ?
			if( isset( $this->params['help'] ) ) {
				$this->_printHelp();
				exit( 0 );
			}

			/// Paramétrage
			$continue = true;
			foreach( $this->possibleParams as $param => $possibleValues ) {
				if( isset( $this->params[$param] ) ) {
					if( is_string( $this->params[$param] ) && in_array( $this->params[$param], $possibleValues ) ) {
						$defaultValue = $this->{$param};
						$value = ( is_bool( $defaultValue ) ? ( ( $this->params[$param] == 'true' ) ? true : false ) : $this->params[$param] );
						$this->{$param} = $value;
					}
					else {
						$continue = false;
						$this->err( "Valeur erronée pour le paramètre -{$param} ({$this->params[$param]})" );
						$this->_printHelpParam( $param );
					}
				}
			}
			if( $continue == false ) {
				exit( 1 );
			}

			/// Nom du fichier et titre de la page
			$this->outfile = sprintf( '%s-%s-%s.html', $this->script, date( 'Ymd-His' ), $this->type );
			$this->outfile = APP_DIR.'/tmp/logs/'.$this->outfile;
			$this->pageTitle = sprintf( $this->pageTitle, date( 'd-m-Y H:i:s' ) );

			App::Import( 'Helper', 'Html' );
			$this->Html = new HtmlHelper();
		}

		/**
		*
		*/

        function row( $result ) {
			$p1 = $this->Personne->findById( Set::classicExtract( $result, 'p1.id' ), null, null, 2 );
			$p2 = $this->Personne->findById( Set::classicExtract( $result, 'p2.id' ), null, null, 2 );

			$return = '';

			foreach( array( $p1, $p2 ) as $p ) {
				$row = array();
				foreach( $this->fields as $field ) {
					$row[] = Set::classicExtract( $p, $field );
				}
				$row[] = count( Set::classicExtract( $p, 'Foyer.Personne' ) );
				$row[] = count( Set::classicExtract( $p, 'Contratinsertion' ) );
				$orientstructs = Set::classicExtract( $p, 'Orientstruct' );
				foreach( $orientstructs as $key => $orientstruct ) {
					if( Set::classicExtract( $orientstruct, 'statut_orient' ) == 'Non orienté' ) {
						unset( $orientstructs[$key] ); // FIXME -> OK ?
					}
				}
				$row[] = count( $orientstructs ); // FIXME: rempli ou pas ?
				$row[] = count( Set::classicExtract( $p, 'Rendezvous' ) );
				$dsp = Set::classicExtract( $p, 'Dsp' );
				foreach( array( 'id', 'personne_id' ) as $rField ) {
					unset( $dsp[$rField] );
				}
				$row[] = count( Set::filter( $dsp ) );

				$Titresejour = Set::filter( Set::classicExtract( $p, 'Titresejour' ) );
				$row[] = ( empty( $Titresejour ) ? 0 : 1 );

				$grossesses = $this->Personne->query( 'SELECT COUNT(*) AS count FROM grossesses WHERE personne_id = '.Set::classicExtract( $p, 'Personne.id' ) );
				$row[] = Set::classicExtract( $grossesses, '0.0.count' );

				$row[] = count( Set::classicExtract( $p, 'Activite' ) );

				$Avispcgpersonne = Set::filter( Set::classicExtract( $p, 'Avispcgpersonne' ) );
				$row[] = ( empty( $Avispcgpersonne ) ? 0 : 1 );

				$return .= $this->Html->tableCells( $row, array( 'class' => 'odd' ), array( 'class' => 'even' ) );
			}

			return $this->Html->tag( 'tbody', $return );
		}

		/**
		*
		*/

        function table( $results ) {
			$return = '';
			if( !empty( $results ) ) {
				$return .= '<table>';
				$return .= '<thead>'.$this->Html->tableHeaders( $this->headers ).'</thead>';
				foreach( $results as $result ) {
					$return .= $this->row( $result );
				}
				$return .= '</table>';
			}

			return $return;
		}

		/**
		*
		*/

        function main() {
            ///   Démarrage du script
            $this_start = microtime( true );
            echo "{$this->pageTitle}\n";
            echo "Demarrage du script: ".date( 'd-m-Y H:i:s' )."\n";
            echo "Fichier de rapport: ".$this->outfile."\n";
			$this->hr();

// 			$this->Personne->begin();

			$queries = array(
				/// Recherche des personnes avec NIR en doublon dans des foyers différents
				'nirsdoublonsfoyersdiff' => array(
					'title' => 'Personnes avec NIR en doublon dans des foyers différents',
					'sql' => "SELECT p1.id AS p1__id, p2.id AS p2__id, p1.foyer_id AS p1__foyer_id, p2.foyer_id AS p2__foyer_id, p1.nom AS p1__nom, p1.prenom AS p1__prenom, p1.dtnai AS p1__dtnai, p1.nomnai AS p1__nomnai
								FROM personnes AS p1, personnes AS p2
								WHERE p1.nir = p2.nir
									AND p1.id < p2.id
									AND p1.foyer_id <> p2.foyer_id
									AND p1.nir <> '' AND p1.nir IS NOT NULL
								ORDER BY p1.nom ASC, p1.prenom ASC
								{$this->limit};"
				),
				/// Recherche des personnes avec NIR en doublon dans le même foyer
				'nirsdoublonsmemefoyer' => array(
					'title' => 'Personnes avec NIR en doublon dans le même foyer',
					'sql' => "SELECT
								p1.id AS p1__id,
								p1.foyer_id AS p1__foyer_id,
								p1.nom AS p1__nom,
								p1.prenom AS p1__prenom,
								p1.dtnai AS p1__dtnai,
								p1.nomnai AS p1__nomnai,
								p2.id AS p2__id,
								p2.foyer_id AS p2__foyer_id
							FROM personnes AS p1, personnes AS p2
							WHERE p1.nir = p2.nir
								AND p1.id < p2.id
								AND p1.foyer_id = p2.foyer_id
								AND p1.nir <> '' AND p1.nir IS NOT NULL
							ORDER BY p1.nom ASC, p1.prenom ASC
							{$this->limit};"
				),
				/// Recherche des personnes avec au moins un NIR manquant en doublon dans des foyers différents
				'personnesdoublonsfoyersdiff' => array(
					'title' => 'Personnes avec au moins un NIR manquant en doublon dans des foyers différents',
					'sql' => "SELECT
								p1.id AS p1__id,
								p1.foyer_id AS p1__foyer_id,
								p1.nom AS p1__nom,
								p1.prenom AS p1__prenom,
								p1.dtnai AS p1__dtnai,
								p1.nomnai AS p1__nomnai,
								p2.id AS p2__id,
								p2.foyer_id AS p2__foyer_id
							FROM personnes AS p1, personnes AS p2
							WHERE p1.nom ILIKE p2.nom
								AND p1.prenom ILIKE p2.prenom
								AND p1.dtnai = p2.dtnai
								AND p1.id < p2.id
								AND p1.foyer_id <> p2.foyer_id
								AND ( ( p1.nir = '' OR p1.nir IS NULL ) OR ( p2.nir = '' OR p2.nir IS NULL ) )
							ORDER BY p1.nom ASC, p1.prenom ASC
							{$this->limit};"
				),
				/// Recherche des personnes avec au moins un NIR manquant en doublon dans le même foyer
				'personnesdoublonsmemefoyer' => array(
					'title' => 'Personnes avec au moins un NIR manquant en doublon dans le même foyer',
					'sql' => "SELECT
								p1.id AS p1__id,
								p1.foyer_id AS p1__foyer_id,
								p1.nom AS p1__nom,
								p1.prenom AS p1__prenom,
								p1.dtnai AS p1__dtnai,
								p1.nomnai AS p1__nomnai,
								p2.id AS p2__id,
								p2.foyer_id AS p2__foyer_id
							FROM personnes AS p1, personnes AS p2
							WHERE p1.nom ILIKE p2.nom
								AND p1.prenom ILIKE p2.prenom
								AND p1.dtnai = p2.dtnai
								AND p1.id < p2.id
								AND p1.foyer_id = p2.foyer_id
								AND ( ( p1.nir = '' OR p1.nir IS NULL ) OR ( p2.nir = '' OR p2.nir IS NULL ) )
							ORDER BY p1.nom ASC, p1.prenom ASC
							{$this->limit};"
				),
				/// Recherche des personnes demandeurs ou conjoints multiples au sein d'un foyer
				'demcjtmultiples' => array(
					'title' => 'Personnes demandeurs ou conjoints multiples au sein d\'un foyer',
					'sql' => "SELECT
								p1.id AS p1__id,
								p1.foyer_id AS p1__foyer_id,
								p1.nom AS p1__nom,
								p1.prenom AS p1__prenom,
								p1.dtnai AS p1__dtnai,
								p1.nomnai AS p1__nomnai,
								p2.id AS p2__id,
								p2.foyer_id AS p2__foyer_id/*,
								pr2.rolepers AS p2__rolepers*/
							FROM personnes AS p1,
								personnes AS p2,
								prestations AS pr1,
								prestations AS pr2
							WHERE p1.foyer_id = p2.foyer_id
								AND p1.id < p2.id
								AND pr1.personne_id = p1.id
								AND pr2.personne_id = p2.id
								AND pr1.natprest = 'RSA'
								AND pr2.natprest = 'RSA'
								AND pr1.rolepers = pr2.rolepers
								AND ( pr1.rolepers = 'DEM' OR pr1.rolepers = 'CJT' )
							ORDER BY p1.foyer_id, p1.nom ASC, p1.prenom ASC
							{$this->limit};"
				),
			);


			// ...
			$this->output .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
								"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
								<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
									<head>
										<title>'.$this->pageTitle.'</title>
										<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
										<style type="text/css" media="all">
											body { font-size: 12px; }
											table { border-collapse: collapse; }
											thead, tbody { border: 3px solid black; }
											th, td { border: 1px solid black; padding: 0.125em 0.25em; }
											tr.odd { background: #eee; }
										</style>
									</head>';
			$this->output .= '<body><h1>'.$this->pageTitle.'</h1>';

			/// Dossiers avec n° de demande en doublon => 0
			// SELECT d1.id, d1.numdemrsa, d1.dtdemrsa FROM dossiers AS d1, dossiers AS d2 WHERE d1.numdemrsa = d2.numdemrsa AND d1.id <> d2.id;

			/// Foyers vides
			if( $this->dossiersvides == true ) {
				$sql = "SELECT dossiers.id, dossiers.numdemrsa, dossiers.dtdemrsa
							FROM dossiers
								INNER JOIN foyers ON ( dossiers.id = foyers.dossier_id )
							WHERE foyers.id NOT IN ( SELECT personnes.foyer_id FROM personnes GROUP BY personnes.foyer_id )
							{$this->limit};";
				$results = $this->Personne->query( $sql );
				echo sprintf( "Dossiers sans personne: %s résultats\n", count( $results ) );
				$this->output .= $this->Html->tag( 'h2', 'Dossiers sans personne' );
				$this->output .= $this->Html->tag( 'p', sprintf( "Dossiers sans personne: %s résultats\n", count( $results ) ) );
				if( ( $this->type == 'full' ) && !empty( $results ) ) {
					$this->output .= '<table>';
					$this->output .= '<thead>'.$this->Html->tableHeaders( array( 'Id', 'Numdemrsa', 'dtdemrsa' ) ).'</thead>';
					$this->output .= $this->Html->tableCells( Set::classicExtract( $results, '{n}.0' ), array( 'class' => 'odd' ), array( 'class' => 'even' ) );
					$this->output .= '</table>';
				}
				$this->hr();
			}

			/// Dossiers contenant des personnes mais sans demandeur RSA
			if( $this->dossierssansdemandeur == true ) {
				$sql = "SELECT dossiers.id, dossiers.numdemrsa, dossiers.dtdemrsa
							FROM dossiers
								INNER JOIN foyers ON dossiers.id = foyers.dossier_id
							WHERE foyers.id NOT IN ( SELECT personnes.foyer_id FROM personnes INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.rolepers = 'DEM' AND prestations.natprest = 'RSA' ) )
								AND foyers.id IN ( SELECT personnes.foyer_id FROM personnes GROUP BY personnes.foyer_id )
							{$this->limit};";
				$results = $this->Personne->query( $sql );
				echo sprintf( "Dossiers contenant des personnes mais sans demandeur RSA: %s résultats\n", count( $results ) );
				$this->output .= $this->Html->tag( 'h2', 'Dossiers contenant des personnes mais sans demandeur RSA' );
				$this->output .= $this->Html->tag( 'p', sprintf( "Dossiers contenant des personnes mais sans demandeur RSA: %s résultats\n", count( $results ) ) );
				if( ( $this->type == 'full' ) && !empty( $results ) ) {
					$this->output .= '<table>';
					$this->output .= '<thead>'.$this->Html->tableHeaders( array( 'Id', 'Numdemrsa', 'dtdemrsa' ) ).'</thead>';
					$this->output .= $this->Html->tableCells( Set::classicExtract( $results, '{n}.0' ), array( 'class' => 'odd' ), array( 'class' => 'even' ) );
					$this->output .= '</table>';
				}
				$this->hr();
			}

			/// Doublons sur les personnes
			foreach( $queries as $name => $query ) {
				if( $this->{$name} == true ) {
					$results = $this->Personne->query( $query['sql'] );
					echo sprintf( "%s: %s personnes à traiter\n", $query['title'], count( $results ) );
					$this->output .= $this->Html->tag( 'h2', $query['title'] );
					$this->output .= $this->Html->tag( 'p', sprintf( "%s personnes en doublon", count( $results ) ) );
					if( $this->type == 'full' ) {
						$this->output .= $this->table( $results );
					}
					$this->hr();
				}
			}

			$this->output .= '</body>';
			$this->output .= '</html>';

			file_put_contents( $this->outfile, $this->output );

// 			$this->Personne->commit();
			echo "Script termine avec succes: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )'."\n";
			$this->hr();
			return 0;
        }
    }
?>