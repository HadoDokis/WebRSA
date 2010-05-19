<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Infofinanciere');

	class InfofinanciereTestCase extends CakeAppModelTestCase {

		function testRange() {
			$result=$this->Infofinanciere->range();
			$expected=array(
				'minYear' => 2009,
				'maxYear' => 2010
			);
			$this->assertEqual($expected,$result);
		}

	}
?>
