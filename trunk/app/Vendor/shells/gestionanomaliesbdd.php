<?php
	/**
	* http://bakery.cakephp.org/articles/Jippi/2007/12/02/emailcomponent-in-a-cake-shell
	*/

	App::import( 'Core', array( 'Controller' ) );
	App::import( 'Component', array( 'Gestionanomaliesbdd' ) );

	class GestionanomaliesbddShell extends AppShell
	{
		public $uses = array();

		public $Controller = null;

		public $solve = false;

		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false,
			'solve' => false,
		);

		/**
		* Initialisation du contrôleur et du component, lecture des paramètres.
		*/
		public function initialize() {
			parent::initialize();

			$this->Controller =& new Controller();
			$this->Controller->Gestionanomaliesbdd =& new GestionanomaliesbddComponent( null );
			$this->Controller->Gestionanomaliesbdd->startup( $this->Controller );

			$this->solve = $this->_getNamedValue( 'solve', 'boolean' );
		}

		/**
		*
		*/
		public function main() {
			$start = microtime( true );

			$fonctions = array(
				'adressesSansAdressesfoyers',
				'prestationsMemeNatureEtMemeRole',
				'adressesPourPlusieursAdressesfoyers',
				'adressesfoyersEnDoublon',
				'personnesSansPrestationSansEntreeMetier',
			);

			foreach( $fonctions as $fonction ) {
				$return = $this->Controller->Gestionanomaliesbdd->{$fonction}( $this->solve );
				$return = ( is_bool( $return ) ? ( ( $return ) ? 'true' : 'false' ) : $return );
				$this->out( str_pad( __d( 'gestionanomaliebdd', "Component::{$fonction}" ), 130 ).$return );
			}

			$this->out( sprintf( "\nExécuté en %s secondes.", number_format( microtime( true ) - $start, 2, ',', ' ' ) ) );
		}
	}
?>