<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Avispcgpersonne');

	class AvispcgpersonneTestCase extends CakeAppModelTestCase
	{

		function testIdFromDossierId() {
			$dossier_id = '4004';
			$this->assertEqual(2, $this->Avispcgpersonne->idFromDossierId($dossier_id));
			$dossier_id = '1';
			$this->assertNull($this->Avispcgpersonne->idFromDossierId($dossier_id));
		}

	}
?>
