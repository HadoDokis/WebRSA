<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Relancenonrespectsanctionep93');

	class Relancenonrespectsanctionep93TestCase extends CakeAppModelTestCase {
		
		function testCheckForRelance() {
			$check;
		}

		function testSaveCohorte() {
			$newdata;
			$data;
		}

		function testSearch() {
			$search;
		}

		function testQdSearchRelances() {
			$search;
		}

		function testCheckCompareError() {
			$datas;
		}

		function testErreursPossibiliteAjout() {
			$personne_id;
		}

		function testDateRelanceMinimale() {
			$typerelance;
			$numrelance;
			$data;
		}

		function testGetDataForPdf() {
			$id;
		}

		function testGeneratePdf() {
			$id;
		}

		function testAfterSave() {
			$created;
		}
	}

?>
