<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Comiteapre');

	class ComiteapreTestCase extends CakeAppModelTestCase {

		// test fonction search
		function testSearch() {
			$criterescomite = array(
				'Comiteapre' => array(
					'datecomite' => true,
					'datecomite_from' => array(
							'year' => 2010,
							'month' => 11,
							'day' => 13,
								),
					'datecomite_to' => array(
							'year' => 2010,
							'month' => 11,
							'day' => 14,
								),
					'heurecomite' => true,
					'heurecomite_from' => array(
							'hour' => 12,
							'min' => 30,
								),
					'heurecomite_to' => array(
							'hour' => 14,
							'min' => 45,
								),
						),
					);
			$expected = array(
				'fields' => array('"Comiteapre"."id"', '"Comiteapre"."datecomite"', '"Comiteapre"."heurecomite"', 						'"Comiteapre"."lieucomite"', '"Comiteapre"."intitulecomite"', '"Comiteapre"."observationcomite"'),
				'recursive' => '-1',
				'order' => array('"Comiteapre"."datecomite" ASC'),
				'conditions' => array("Comiteapre.datecomite BETWEEN '2010-11-13' AND '2010-11-14'",
						"Comiteapre.heurecomite BETWEEN '12:30' AND '14:45'"),
					); 
			$result = $this->Comiteapre->search(null, $criterescomite);
			$this->assertTrue($result);
			$this->assertEqual($result, $expected);
		}
	}

?>
