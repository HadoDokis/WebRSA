<?php
	/**
	 * Code source de la classe PostgresMaintenanceShell.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppShell', 'Console/Command' );
	App::uses( 'ConnectionManager', 'Model' );

	/**
	 * La classe PostgresMaintenanceShell fournit des méthodes de maintenance de
	 * base de données PostgreSQL.
	 *
	 * @see Pgsqlcake.MaintenanceShell (sera dépréciée par cette classe-ci)
	 *
	 * @package Postgres
	 * @subpackage Console.Command
	 */
	class PostgresMaintenanceShell extends AppShell
	{
		/**
		 * La constante à utiliser dans la méthode _stop() en cas de succès.
		 */
		const SUCCESS = 0;

		/**
		 * La constante à utiliser dans la méthode _stop() en cas d'erreur.
		 */
		const ERROR = 1;

		/**
		 * Le connection vers la base de données.
		 *
		 * @var DboSource
		 */
		public $Dbo = null;

		/**
		 * Liste des sous-commandes et de leur description.
		 *
		 * @var array
		 */
		public $commands = array(
			'all' => array(
				'help' => "Effectue toutes les opérations de maintenance ( reindex, sequence, vacuum )."
			),
			'sequences' => array(
				'help' => 'Mise à jour des compteurs des champs auto-incrémentés'
			),
			'vacuum' => array(
				'help' => 'Nettoyage de la base de données et mise à jour des statistiques du planificateur'
			),
			'reindex' => array(
				'help' => 'Reconstruction des indexes'
			),
		);

		/**
		 * Liste des options et de leur description.
		 *
		 * @var array
		 */
		public $options = array(
			'connection' => array(
				'short' => 'c',
				'help' => 'Le nom de la connection à la base de données',
				'default' => 'default',
			)
		);

		/**
		 * Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		 * PostgreSQL valide, on affiche la version de PostgreSQL utilisée.
		 */
		public function startup() {
			parent::startup();

			$connection = ( isset( $this->params['connection'] ) ? $this->params['connection'] : 'default' );

			try {
				$this->Dbo = ConnectionManager::getDataSource( $connection );
			} catch( Exception $Exception ) {
				 $this->log( $Exception->getMessage(), LOG_ERR );
			}

			if( !is_a( $this->Dbo, 'DataSource' ) || !$this->Dbo->connected ) {
				$this->err( "Impossible de se connecter avec la connexion {$connection}" );
				$this->_stop( self::ERROR );
				return;
			}

			if( !( $this->Dbo instanceof Postgres ) ) {
				$this->err( "La connexion {$connection} n'utilise pas le driver postgres" );
				$this->_stop( self::ERROR );
				return;
			}

			$result = $this->Dbo->query( 'SELECT version();' );
			$this->out( Hash::get( $result, '0.0.version' ) );
			$this->hr();
			$this->out();
		}

		/**
		 * Reconstruction des indexes.
		 */
		public function reindex() {
			$this->out( date( 'H:i:s' )." - {$this->commands[__FUNCTION__]['help']} (reindex)" );
			$sql = "REINDEX DATABASE {$this->Dbo->config['database']};";
			$success = ( $this->Dbo->query( $sql ) !== false );

			if( $this->command === __FUNCTION__ ) {
				$this->_stop( $success ? self::SUCCESS : self::ERROR );
				return;
			}

			return $success;
		}

		/**
		 * Mise à jour des compteurs des champs auto-incrémentés.
		 */
		public function sequences() {
			$this->out( date( 'H:i:s' )." - {$this->commands[__FUNCTION__]['help']} (sequences)" );

			$success = ( $this->Dbo->begin() !== false );

			$sql = "SELECT table_name AS \"Model__table\",
						column_name	AS \"Model__column\",
						column_default AS \"Model__sequence\"
						FROM information_schema.columns
						WHERE table_schema = 'public'
							AND column_default LIKE 'nextval(%::regclass)'
						ORDER BY table_name, column_name";

			foreach( $this->Dbo->query( $sql ) as $model ) {
				$sequence = preg_replace( '/^nextval\(\'(.*)\'.*\)$/', '\1', $model['Model']['sequence'] );

				$sql = "SELECT setval('{$sequence}', COALESCE(MAX({$model['Model']['column']}),0)+1, false) FROM {$model['Model']['table']};";
				$success = $success && ( $this->Dbo->query( $sql ) !== false );
			}

			if( $success ) {
				$success = ( $this->Dbo->commit() !== false ) && $success;
			}
			else {
				$success = ( $this->Dbo->rollback() !== false ) && $success;
			}

			if( $this->command === __FUNCTION__ ) {
				$this->_stop( $success ? self::SUCCESS : self::ERROR );
				return;
			}

			return $success;
		}

		/**
		 * Nettoyage de la base de données et mise à jour des statistiques du planificateur
		 *
		 * @url http://docs.postgresqlfr.org/8.4/maintenance.html (pas FULL)
		 */
		public function vacuum() {
			$this->out( date( 'H:i:s' )." - {$this->commands[__FUNCTION__]['help']} (vacuum)" );
			$sql = "VACUUM ANALYZE;";
			$success = ( $this->Dbo->query( $sql ) !== false );

			if( $this->command === __FUNCTION__ ) {
				$this->_stop( $success ? self::SUCCESS : self::ERROR );
				return;
			}

			return $success;
		}

		/**
		 * Effectue toutes les opérations de maintenance ( reindex, sequence, vacuum ).
		 */
		public function all() {
			$success = true;

			$commands = Hash::remove( $this->commands, __FUNCTION__ );
			foreach( array_keys( $commands ) as $command ) {
			$success = $success && $this->{$command}();
			}

			$this->_stop( $success ? self::SUCCESS : self::ERROR );
		}

		/**
		 * Paramétrages et aides du shell.
		 */
		public function getOptionParser() {
			$Parser = parent::getOptionParser();

			$Parser->description( 'Script de maintenance de base de données PostgreSQL' );
			$Parser->addSubcommands( $this->commands );
			$Parser->addOptions( $this->options );

			return $Parser;
		}
	}
?>