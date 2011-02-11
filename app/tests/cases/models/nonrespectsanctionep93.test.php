<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Nonrespectsanctionep93');

	class Nonrespectsanctionep93TestCase extends CakeAppModelTestCase {
		
		function testVerrouiller() {
			$seanceep_id = null;
			$etape = null;
			$this->assertTrue($this->Nonrespectsanctionep93->verrouiller($seanceep_id, $etape));
		}

		function testQdDossiersParListe() {
			$seanceep_id = '3';
			$niveauDecision = 'cg';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($seanceep_id, $niveauDecision);
			debug($return);
		}

		function testPrepareFormData() {
			$seanceep_id = '3';
			$datas = ;
			$niveauDecision;
		}

		function testSaveDecisions() {
			$data;
			$niveauDecision;
		}

		function testFinaliser() {
			$seanceep_id;
			$etape;
		}

		function testContainPourPv() {

		}

		function testQdProcesVerbal() {

		}
	}

?>
