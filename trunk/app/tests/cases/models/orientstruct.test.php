<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Orientstruct');

	class OrientstructTestCase extends CakeAppModelTestCase {

		function testDossierId() {
			$result=$this->Orientstruct->dossierId(1);
			$this->assertEqual(1,$result);

			//------------------------------------------------------------------

			$result=$this->Orientstruct->dossierId(3);
			$this->assertEqual(2,$result);

			//------------------------------------------------------------------

			$result=$this->Orientstruct->dossierId(666);
			$this->assertNull($result);
		}

	}
?>
