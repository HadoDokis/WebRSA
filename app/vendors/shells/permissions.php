<?php
	/**
	* TODO: le parent_id des Groupes dans les Aros des groupes
	*/

	App::import( 'Core', 'Controller' );
	App::import( 'Component', 'Acl' );
	App::import( 'Component', 'Dbdroits' );
	App::import( 'Controller', 'App' );

    class PermissionsShell extends AppShell
    {
		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false,
			'limit' => null
		);

		public $verbose;
		public $limit;
		public $connection = null;

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* valide
		*/

		public function initialize() {
			parent::initialize();

			$this->Controller =& new Controller();
			$this->Controller->components = array( 'Acl', 'Dbdroits' );
			$this->Controller->uses = array();
			$this->Controller->constructClasses();
			$this->Controller->Component->initialize( $this->Controller );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/
		public function _welcome() {
			$this->out();
			$this->out( 'Mise à jour de la table acos' );
			$this->out();
			/*$this->hr();
			$this->out();
			$this->out( 'Connexion : '. $this->connection->configKeyName );
			$this->out( 'Base de donnees : '. $this->connection->config['database'] );
			$this->out( 'Limite : '. $this->_valueToString( $this->limit ) );
			$this->out( 'Journalisation : '. $this->_valueToString( $this->log ) );
			$this->out( 'Fichiers de rapport : '. $this->_valueToString( $this->verbose ) );
			$this->out();*/
			$this->hr();
		}

		/**
		*
		*/
		protected function _addAcos() {
			$this->Controller->Dbdroits->majActions();
			return true;
		}

		/**
		*
		*/
		public function main() {
			$success = true;
			$success = $this->_addAcos() && $success;

			$this->_stop( !$success );
		}
    }
?>
