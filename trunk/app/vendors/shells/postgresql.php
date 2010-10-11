<?php
	/**
	* INFO: http://docs.postgresqlfr.org/8.2/maintenance.html
	*/

	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

    class PostgresqlShell extends AppShell
    {
		public $allConnections = array();

		public $commandDescriptions = array(
			'reindex' => 'Reconstruction des indexes',
			'sequences' => 'Mise à jour des compteurs des champs auto-incrémentés',
			'vacuum' => 'Nettoyage de la base de données et mise à jour des statistiques du planificateur'
		);

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false
		);

		public $verbose;

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* PostgreSQL valide
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

			if( $this->connection->config['driver'] != 'postgres' ) {
				$this->error( "La connexion {$connectionName} n'utilise pas le driver postgres" );
			}
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$psqlVersion = $this->connection->query( 'SELECT version();' );
			$psqlVersion = Set::classicExtract( $psqlVersion, '0.0.version' );

			$this->out();
			$this->out( 'Script de maintenance de base de données PostgreSQL' );
			$this->out();
			$this->hr();
			$this->out();
			$this->out( 'Connexion : '. $this->connection->configKeyName );
			$this->out( 'Base de données : '. $this->connection->config['database'] );
			$this->out( $psqlVersion );
			$this->out();
			$this->hr();
		}

		/**
		* Effectue une requête SQL simple et affiche ou retourne si la requête
		* s'est déroulée sans erreur.
		*/

		protected function _singleQuery( $sql ) {
			$this->connection->query( $sql );

			if( $this->verbose ) {
				$this->out(
					sprintf(
						"$sql\t-- terminé avec %s en %s ms",
						( empty( $this->connection->error ) ? 'succès' : 'erreur' ),
						$this->connection->took
					)
				);
			}

			if( $this->command == 'all' ) {
				return empty( $this->connection->error );
			}
			else {
				$this->out();
				return $this->_stop( !empty( $this->connection->error ) );
			}
		}

		/**
		* Reconstruction des indexes
		*/

		public function reindex() {
			$this->out( "\n".date('H:i:s')." - {$this->commandDescriptions['reindex']} (reindex)" );
			return $this->_singleQuery( "REINDEX DATABASE {$this->connection->config['database']};" );
		}

		/**
		* Mise à jour des compteurs des champs auto-incrémentés
		*/

		public function sequences() {
			$this->out( "\n".date('H:i:s')." - {$this->commandDescriptions['sequences']} (sequences)" );

			if( $this->verbose ) {
				$this->out( 'BEGIN;' );
			}
			$this->connection->query( 'BEGIN;' );

			$took = 0;
			$success = true;

			$sql = "SELECT table_name AS \"Model__table\",
						column_name	AS \"Model__column\",
						column_default AS \"Model__sequence\"
						FROM information_schema.columns
						WHERE table_schema = 'public'
							AND column_default LIKE 'nextval(%::regclass)'
						ORDER BY table_name, column_name";

			foreach( $this->connection->query( $sql ) as $model ) {
				$sequence = preg_replace( '/^nextval\(\'(.*)\'.*\)$/', '\1', $model['Model']['sequence'] );

				$sql = "SELECT setval('{$sequence}', max({$model['Model']['column']})) FROM {$model['Model']['table']};";
				$result = $this->connection->query( $sql );

				$tmpSuccess = empty( $this->connection->error );
				$success = $success && $tmpSuccess;

				if( $this->verbose ) {
					$this->out(
						sprintf(
							"$sql\t-- terminé avec %s en %s ms - nouvelle valeur: %s",
							( empty( $this->connection->error ) ? 'succès' : 'erreur' ),
							$this->connection->took,
							$result[0][0]['setval']
						)
					);
				}
			}

			if( $success ) {
				if( $this->verbose ) {
					$this->out( 'COMMIT;' );
				}
				$this->connection->query( 'COMMIT;' );
			}
			else {
				if( $this->verbose ) {
					$this->err( 'ROLLBACK;' );
				}
				$this->connection->query( 'ROLLBACK;' );
			}

			if( $this->command == 'all' ) {
				return $success;
			}
			else {
				$this->out();
				return $this->_stop( !$success );
			}
		}

		/**
		* Nettoyage de la base de données et mise à jour des statistiques du planificateur
		* INFO: pas FULL -> http://docs.postgresqlfr.org/8.2/maintenance.html
		*/

		public function vacuum() {
			$this->out( "\n".date('H:i:s')." - {$this->commandDescriptions['vacuum']} (vacuum)" );
			return $this->_singleQuery( "VACUUM ANALYZE;" );
		}

		/**
		* Réalisation de toutes les opérations
		*/

		public function all() {
			$error = false;
			$operations = array(
				'vacuum',
				'sequences',
				'reindex'
			);

			foreach( $operations as $operation ) {
				$error = !$this->{$operation}() && $error;
			}

			$this->out();
			$this->_stop( $error );
		}

		/**
		* Par défaut, on affiche l'aide
		*/

		public function main() {
			$this->help();
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake postgresql <commande> <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} all\n\t\tEffectue toutes les opérations de maintenance ( ".implode( ', ', $this->operations )." ).");
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} reindex\n\t\t{$this->commandDescriptions['reindex']}");
			$this->out("\n\t{$this->shell} sequences\n\t\t{$this->commandDescriptions['sequences']}");
			$this->out("\n\t{$this->shell} vacuum\n\t\t{$this->commandDescriptions['vacuum']}");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les commandes SQL exéctuées ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>