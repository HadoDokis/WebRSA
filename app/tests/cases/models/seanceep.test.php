<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Seanceep');

	class SeanceepTestCase extends CakeAppModelTestCase {

		function testSearch() {
			$criteresseanceep;
		}

		function testThemesTraites() {
			$id;
		}

		function testSaveDecisions() {
			$seanceep_id;
			$data;
			$niveauDecision;
		}

		function testDossiersParListe() {
			$seanceep_id;
			$niveauDecision;
		}

		function testPrepareFormData() {
			$seanceep_id;
			$dossiers;
			$niveauDecision;
		}

		function testFinaliser() {
			$seanceep_id;
			$niveauDecision;
		}

		function testClotureSeance() {
			$datas;
		}

		function testGetPdfPv() {
			$seanceep_id;
		}

		function testGetPdfOrdreDuJour() {
			$seanceep_id;
		}
	}
?>
