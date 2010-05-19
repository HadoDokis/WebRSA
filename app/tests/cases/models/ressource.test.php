<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Ressource');

	class RessourceTestCase extends CakeAppModelTestCase
	{

		/**
		* Test de la fonction dossierId
		*/
		function testDossierId() {
			// Ressource existante
			$result = $this->Ressource->dossierId(1);
			$this->assertEqual(1, $result);

			// Ressource inexistante
			$result = $this->Ressource->dossierId(666);
			$this->assertNull($result);

			// Ressource existante mais pas la personne
			$result = $this->Ressource->dossierId(555);
			$this->assertNull($result);		

			// Ressource existante, la personne aussi mais pas le foyer
			$result = $this->Ressource->dossierId(556);
			$this->assertNull($result);		
		}

	}
?>
