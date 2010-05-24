<?php
	/**
	* INFO: http://docs.postgresqlfr.org/8.2/maintenance.html
	*/

	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

    class AnomaliesShell extends AppShell
    {
		public $allConnections = array();

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false
		);

		public $verbose; // TODO

		protected $_checks = array(
			array(
				'text' => 'foyers vides',
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( foyers.id )
										FROM foyers
								EXCEPT
									SELECT DISTINCT( personnes.foyer_id )
										FROM personnes
							) AS FOO'
			),
			array(
				'text' => 'dossiers sans foyer',
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( dossiers_rsa.id )
										FROM dossiers_rsa
								EXCEPT
									SELECT DISTINCT( foyers.dossier_rsa_id )
										FROM foyers
							) AS FOO'
			),
			array(
				'text' => 'foyers sans demandeur RSA',
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( foyers.id )
										FROM foyers
								EXCEPT
									SELECT DISTINCT( personnes.foyer_id )
										FROM personnes
										INNER JOIN prestations ON (
											prestations.personne_id = personnes.id
											AND prestations.natprest = \'RSA\'
											AND prestations.rolepers = \'DEM\'
										)
							) AS FOO'
			),
			array(
				'text' => 'adresses_foyers de rang incorrect',
				'sql' => 'SELECT COUNT(adresses_foyers.*)
							FROM adresses_foyers
							WHERE
								adresses_foyers.rgadr NOT IN ( \'01\', \'02\', \'03\' );'
			),
			array(
				'text' => 'adresses_foyers en doublons',
				'sql' => 'SELECT COUNT(a1.*)
							FROM adresses_foyers AS a1,
								adresses_foyers AS a2
							WHERE
								a1.id <> a2.id
								AND a1.foyer_id = a2.foyer_id
								AND a1.rgadr = a2.rgadr;'
			),
			array(
				'text' => 'personnes en doublons',
				'sql' => 'SELECT COUNT(p1.*)
							FROM personnes p1,
								personnes p2
							WHERE p1.id <> p2.id
								AND
								(
									( LENGTH(p1.nir) = 15 AND p1.nir = p2.nir )
									OR ( p1.nom = p2.nom AND p1.prenom = p2.prenom AND p1.dtnai = p2.dtnai )
								)'
			),
			array(
				'text' => 'personnes sans prestation RSA',
// 				'sql' => 'SELECT COUNT(personnes.*)
// 							FROM personnes
// 							WHERE personnes.id NOT IN (
// 								SELECT prestations.personne_id
// 									FROM prestations
// 									WHERE prestations.natprest = \'RSA\'
// 							);'
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( personnes.id )
										FROM personnes
								EXCEPT
									SELECT DISTINCT( prestations.personne_id )
										FROM prestations
										WHERE prestations.natprest = \'RSA\'
							) AS FOO'
			),
			array(
				'text' => 'prestations en doublons pour une natprest',
				'sql' => 'SELECT COUNT(p1.*)
							FROM prestations p1,
								prestations p2
							WHERE p1.id <> p2.id
								AND p1.personne_id = p2.personne_id
								AND p1.natprest = p2.natprest
						-- 		AND p1.rolepers = p2.rolepers
						-- 	ORDER BY p1.personne_id ASC, p1.natprest ASC, p1.rolepers ASC'
			),
			array(
				'text' => 'orientsstrcuts pour des non demandeurs ou conjoints RSA',
				'sql' => 'SELECT COUNT(orientsstructs.*)
					FROM orientsstructs
					WHERE
						orientsstructs.statut_orient = \'Orienté\'
						AND orientsstructs.personne_id NOT IN (
							SELECT prestations.personne_id
								FROM prestations
								WHERE prestations.natprest = \'RSA\'
									AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
						);'
			),
			array(
				'text' => 'apres pour des non demandeurs ou conjoints RSA',
				'sql' => 'SELECT COUNT(apres.*)
							FROM apres
							WHERE
								apres.personne_id NOT IN (
									SELECT prestations.personne_id
										FROM prestations
										WHERE prestations.natprest = \'RSA\'
											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
								);'
			),

			array(
				'text' => 'dsps pour des non demandeurs ou conjoints RSA',
				'sql' => 'SELECT COUNT(dsps.*)
							FROM dsps
							WHERE
								dsps.personne_id NOT IN (
									SELECT prestations.personne_id
										FROM prestations
										WHERE prestations.natprest = \'RSA\'
											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
								);'
			)
		);

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* valide
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
			$connectionName = $this->_getNamedValue( 'connection', 'string' );

			try {
				$this->connection = @ConnectionManager::getDataSource( $connectionName );
			} catch (Exception $e) {
			}

			if( !$this->connection || !$this->connection->connected ) {
				$this->error( "Impossible de se connecter avec la connexion {$connectionName}" );
			}
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$this->out();
			$this->out( 'Détection des anomalies sur une BDD webrsa' );
			$this->out();
			$this->hr();
			$this->out();
			$this->out( 'Connexion : '. $this->connection->configKeyName );
			$this->out( 'Base de données : '. $this->connection->config['database'] );
			$this->out();
			$this->hr();
		}

		/**
		* Par défaut, on affiche l'aide
		*/

		public function main() {
			$success = true;
			$this->out();

			foreach( $this->_checks as $check ) {
				$result = $this->connection->query( $check['sql'] );
				$result = Set::classicExtract( $result, '0.0.count' );
				$this->out(
					sprintf(
						"%s\t%s\t (%s ms)",
						str_pad( $check['text'], 60, " ", STR_PAD_RIGHT ),
						( $this->connection->error ? 'erreur' : $result ),
						$this->connection->took
					)
				);
				$success = ( !$this->connection->error ) && $success;
			}

			$this->out();

			$this->_stop( !$success );
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake {$this->script} <commande> <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>