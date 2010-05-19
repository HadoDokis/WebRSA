<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Avispcgpersonne');

	class AvispcgpersonneTestCase extends CakeAppModelTestCase
	{

		function testIdFromDossierId() {
			$this->assertEqual(1,$this->Avispcgpersonne->idFromDossierId(1));
			$this->assertNull($this->Avispcgpersonne->idFromDossierId(2));
			$this->assertNull($this->Avispcgpersonne->idFromDossierId(666));
		}

	}
?>
