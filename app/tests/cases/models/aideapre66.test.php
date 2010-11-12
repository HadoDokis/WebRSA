<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Aideapre66');

	class Aideapre66TestCase extends CakeAppModelTestCase {

		//test de la fonction plafondMontantAideapre()
		function testPlafondMontantAideapre() {
			$result = $this->Aideapre66->plafondMontantAideapre(1);
			$this->assertEqual($result, 1);
		}

		//test de la fonction
		function test_nbrNormalPieces() {
			$result = $this->Aideapre66->_nbrNormalPieces();
			$this->assertIsA($result, bool);
		}

		function test_details() {
			$expected = array(
				'Piecepresente' => array(
						'Typeaideapre66' => 0,
						),
				'Piecemanquante' => array(
						'Typeaideapre66' => 0,
						),
				);
			$result = $this->Aideapre66->_details(1);
			$this->assertEqual($result, $expected);
		}

		function testAfterSave() {
			$result = $this->Aideapre66->afterSave();
			$this->assertNull($result);
		}
	}

?>
