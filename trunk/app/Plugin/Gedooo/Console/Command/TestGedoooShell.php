<?php
//	App::import( 'Model', 'AppModel' );
//	App::import( 'Behavior', 'Gedooo.Gedooo' );
//	App::import( 'Model', 'Appchecks.Check' );

	App::uses( 'XShell', 'Console/Command' );
	App::uses( 'GedoooBehavior', 'Gedooo.Model/Behavior' );
	App::uses( 'Check', 'Appchecks.Model' );
//	App:uses( 'Appchecks.Check', 'Model' );
	/**
	 * Shell permettant de tester l'état du serveur Gedooo.
	 * Un test d'impression sera également réalisé.
	 *
	 * Codes de retour:
	 * 	0: succès
	 * 	1: erreur lors de la connexion au serveur Gedooo
	 * 	2: erreur lors de la génération du document de test
	 */
	class TestGedoooShell extends XShell
	{

		const success = 0;
		const error = 1;

		/**
		 * Test du serveur et envoi d'un code de retour explicite.
		 */
		public function main() {
			$GedModel = ClassRegistry::init( 'User' );
			$GedModel->Behaviors->load( 'Gedooo.Gedooo' );

			$CheckModel = ClassRegistry::init( 'Appchecks.Check' );

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
		}

	}
?>