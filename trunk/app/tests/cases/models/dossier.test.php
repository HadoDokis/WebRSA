<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Dossier');

	class DossierTestCase extends CakeAppModelTestCase {

		function testFindByZones() {
			//debug($this->Dossier->findByZones());
		}
	}
?>
