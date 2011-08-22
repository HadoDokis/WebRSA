<?php
	/**
	* Shell permettant de tester l'état du serveur Gedooo.
	* Un test d'impression sera également réalisé.
	*
	* Codes de retour:
	*	0: succès
	*	1: erreur lors de la connexion au serveur Gedooo
	*	2: erreur lors de la génération du document de test
	*/

	App::import( 'Core', array( 'Controller' ) );
	App::import( 'Component', array( 'Gedooo' ) );

	class TestGedoooShell extends Shell
	{
		const success = 0;
		const serverError = 1;
		const generationError = 2;
		const fileExistsError = 3;

		public $Controller = null;

		/**
		* Initialisation du contrôleur et du component
		*/

		public function initialize() {
			$this->Controller =& new Controller();
			$this->Controller->Gedooo =& new GedoooComponent( null );
			$this->Controller->Gedooo->startup( $this->Controller );
		}

		/**
		* Test du serveur et envoi d'un code de retour explicite.
		*/

		public function main() {
			$response = $this->Controller->Gedooo->check( false, false, true );

			if( !$response['file_exists'] ) {
				$this->err( 'Le fichier '.GEDOOO_TEST_FILE.' n\'existe pas. Impossible de tester le serveur Gedooo.' );
				$this->_stop( TestGedoooShell::fileExistsError );
			}

			if( ( $response['status'] != 200 ) || ( $response['content-type'] != 'text/xml') ) {
				$this->err( 'Impossible de se connecter au serveur Gedooo. Veuillez contacter votre administrateur système.' );
				$this->_stop( TestGedoooShell::serverError );
			}

			if( !$response['print'] ) {
				$this->err( 'Impossible d\'imprimer avec le serveur Gedooo. Veuillez contacter votre administrateur système.' );
				$this->_stop( TestGedoooShell::generationError );
			}

			$this->out( 'Serveur Gedooo fonctionnel.' );
			$this->_stop( TestGedoooShell::success );
		}
	}
?>