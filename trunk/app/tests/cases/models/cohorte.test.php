<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Cohorte');

	class CohorteTestCase extends CakeAppModelTestCase
	{

		function testStructuresAutomatiques() {
			$expected=array(
				2 => array(
					34000 => '2_3'
				)
			);
			$result=$this->Cohorte->structuresAutomatiques();
			$this->assertEqual($result,$expected);
		}

	}
?>
