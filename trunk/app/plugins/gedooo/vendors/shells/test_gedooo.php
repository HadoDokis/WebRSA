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

	App::import( 'Model', array( 'AppModel' ) );
	App::import( 'Behavior', array( 'Gedooo.Gedooo' ) );
	App::import( 'Model', array( 'Appchecks.Check' ) );

	class TestGedoooShell extends Shell
	{
		const success = 0;
		const error = 1;

		/**
		* Test du serveur et envoi d'un code de retour explicite.
		*/

		public function main() {
			$GedModel = ClassRegistry::init( 'User' );
			$GedModel->Behaviors->attach( 'Gedooo' );

			$CheckModel = ClassRegistry::init( 'Check' );

			$success = true;

			// Vérification de la configuration
			$this->out( 'Vérification de la configuration' );
			$configureKeys = $GedModel->Behaviors->Gedooo->gedConfigureKeys( $GedModel );
			foreach( $CheckModel->configure( $configureKeys ) as $key => $result ) {
				$success = $result['success'] && $success;

				$this->out( "\t".( $result['success'] ? 'OK' : 'Erreur' )."\t".str_pad( $key, 20 )."\t".$result['value'] );
			}

			$this->out( " " );

			// Test de l'impression
			$this->out( 'Test de génération de document' );
			foreach( $GedModel->gedTests() as $key => $result ) {
				$success = $result['success'] && $success;

				$this->out( "\t".( $result['success'] ? 'OK' : 'Erreur' )."\t".str_pad( $key, 20 ) );
			}

			$this->_stop( $success ? self::success : self::error );
		}
	}
?>