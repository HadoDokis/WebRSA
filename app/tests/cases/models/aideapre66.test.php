<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Aideapre66');

	class Aideapre66TestCase extends CakeAppModelTestCase {

		// test de la fonction plafondMontantAideapre()
		function testPlafondMontantAideapre() {
			$datas = array(
				'Aideapre66' => array(
					'typeaideapre66_id' => '1',
				),
			);
			$this->Aideapre66->data = $datas;

			$result = $this->Aideapre66->plafondMontantAideapre(array(700));
			$this->assertTrue($result);

			$result = $this->Aideapre66->plafondMontantAideapre(array(77));
			$this->assertTrue($result);

			$result = $this->Aideapre66->plafondMontantAideapre(array(31337));
			$this->assertFalse($result);
		}
/*
		// test de la fonction nbrNormalPieces
		function test_nbrNormalPieces() {
			$result = $this->Aideapre66->_nbrNormalPieces();
			$this->assertFalse($result);
		}


		function test_details() {
			$expected = array(
				'Piecepresente' => array(
						'Typeaideapre66' => 1,
						),
				'Piecemanquante' => array(
						'Typeaideapre66' => 1,
						),
				);
			$result = $this->Aideapre66->_details(1);
			$this->assertEqual($result, $expected);
		}

		function testAfterSave() {
			$created = array(
					'id' => '1',
					'apre_id' => '1',
					'themeapre66_id' => '1',
					'typeaideapre66_id' => '1',
					'montantaide' => null,
					'motivdem' => null,
					'virement' => null,
					'versement' => null,
					'autorisationvers' => null,
					'datedemande' => null,
					'motifrejet' => null,
					'montantpropose' => null,
					'datemontantpropose' => null,
					'decisionapre' => null,
					'montantaccorde' => null,
					'datemontantaccorde' => null,
					'creancier' => null,
					'motifrejetequipe' => null,
				);
			$datas = array('Aideapre66' => $created);
			$this->Aideapre66->data = $datas;
			$result = $this->Aideapre66->afterSave($created);
			debug($result);
			$this->assertNull($result);
		}
*/		
	}

?>
