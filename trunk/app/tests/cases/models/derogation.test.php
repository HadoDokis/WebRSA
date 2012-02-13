<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Derogation');

	class DerogationTestCase extends CakeAppModelTestCase
	{

		function testDossierId() {
			$this->assertEqual(1,$this->Derogation->dossierId(1));
			$this->assertNull($this->Derogation->dossierId(666));
			// valeurs en entrée incohérentes
			$this->assertNull($this->Derogation->dossierId(-42));
			// Erreur PHP -> insére "toto" à la place d'un 'interger'
			//$this->assertNull($this->Derogation->dossierId("toto"));
		}

	}
?>
