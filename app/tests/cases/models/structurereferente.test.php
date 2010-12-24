<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Structurereferente');

	class StructurereferenteTestCase extends CakeAppModelTestCase {

		// list1Options( $conditions = array() )
		function testList1Options() {
			$conditions = null;
			$expected =  array(
					'1_2' => 'Assedic Nimes',
					'3_4' => 'Conseil Général de l\'Hérault',
					'2_3' => 'MSA du Gard',
					'3_5' => 'Organisme ACAL Vauvert',
					'1_1' => 'Pole emploi Mont Sud',
			);
			$result = $this->Structurereferente->list1Options($conditions);
			$this->assertEqual($expected, $result);
		}

		function testListOptions() {
			$result = $this->Structurereferente->listOptions();
			$expected = array(
				'Emploi' => array(
						'2' => 'Assedic Nimes',
						'1' => 'Pole emploi Mont Sud',
					), 
				'Social' => array(
						'4' => 'Conseil Général de l\'Hérault', 
						'5' => 'Organisme ACAL Vauvert', 
					),
				'Socioprofessionnelle' => array(
						'3' => 'MSA du Gard'
					),
				);
			$this->assertEqual($expected, $result);
		}

		function testListePourApre() {
			$result = $this->Structurereferente->listePourApre();
			$this->assertNull($result);
		}

		function testListeParType() {
			$types = null;
			$result = $this->Structurereferente->listeParType($types);
			$expected = array(
				'2' => 'Assedic Nimes',
				'4' => 'Conseil Général de l\'Hérault',
				'3' => 'MSA du Gard',
				'5' => 'Organisme ACAL Vauvert',
				'1' => 'Pole emploi Mont Sud', 
			);
			$this->assertEqual($result, $expected);
		}
	}

?>
