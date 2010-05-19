<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Adressefoyer');

	class AdressefoyerTestCase extends CakeAppModelTestCase {

		function testDossierId() {
			$this->assertEqual(1,$this->Adressefoyer->dossierId(1));
			$this->assertEqual(2,$this->Adressefoyer->dossierId(2));
			$this->assertFalse($this->Adressefoyer->dossierId(666));
		}
	}
?>
