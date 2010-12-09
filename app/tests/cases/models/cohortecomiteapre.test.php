<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Cohortecomiteapre');

	class CohortecomiteapreTestCase extends CakeAppModelTestCase {
		
		// test function testSearch($avisComite, $criterescomite)
		function testSearch() {
			$criterescomite = array(
				'Cohorotecomiteapre' => array(
						'id' => '1',
						'datecomite' => '2009-01-09',
						'heurecomite' => '12:30',
						'lieucomite' => null,
						'intitulecomite' => 'intitulé',
						'observationcomite' => null,				
						),
				);
			$result = $this->Cohortecomiteapre->search('Cohortecomiteapre::aviscomite', $criterescomite);
			$this->assertTrue($result);
		}
		
	}

?>
