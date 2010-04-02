<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Foyer');

	class FoyerTestCase extends CakeAppModelTestCase
	{

		function testDossierId() {
			// Foyer existant
			$result = $this->Foyer->dossierId( 1 );
			$expected = 1;
			$this->assertEqual($result, $expected);

			// Foyer non existant
			$result = $this->Foyer->dossierId( 666 );
			$expected = null;
			$this->assertEqual($result, $expected);
		}

		function testNbEnfants() {
			$result = $this->Foyer->nbEnfants(1);
			$expected = 1;
			$this->assertEqual($result, $expected);

			$result = $this->Foyer->nbEnfants(2);
			$expected = 0;
			$this->assertEqual($result, $expected);
		}

		function testMontantForfaitaire() {
			$result = $this->Foyer->montantForfaitaire(1);
			$expected = true;
			$this->assertEqual($result, $expected);

			$result = $this->Foyer->montantForfaitaire(2);
			$expected = false;
			$this->assertEqual($result, $expected);
		}

	}
?>
