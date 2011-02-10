<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Dossierep');

	class DossierepTestCase extends CakeAppModelTestCase {

		function testThemeTraite() {
			$id;
		}

		function testPrepareFormDataUnique() {
			$dossierep_id;
			$dossier;
			$niveauDecision;
		}

		function testSauvegardeUnique() {
			$dossierep_id;
			$data;
			$niveauDecision;
		}

		function testErreursCandidatePassage() {
			$personne_id;
		}
	}
?>
