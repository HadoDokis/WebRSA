<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'ApreComiteapre');

	class ApreComiteapreTestCase extends CakeAppModelTestCase {

		// test function beforeSave
		function testBeforeSave() {
			$option = array(
				'id' => '1',
				'apre_id' => '1',
				'comiteapre_id' => '1',
				'montantattribue' => 0,
				'observationcomite' => null,
				'decisioncomite' => 'REF',
				'recoursapre' => null,
				'observationrecours' => null,
				'daterecours' => null,
				'comite_pcd_id' => null,
				);
			$result = $this->ApreComiteapre->beforeSave($option);
			$this->assertTrue($result);

			$option = array(
				'id' => '1',
				'apre_id' => '1',
				'comiteapre_id' => '1',
				'montantattribue' => 700,
				'observationcomite' => null,
				'decisioncomite' => 'ACC',
				'recoursapre' => null,
				'observationrecours' => null,
				'daterecours' => null,
				'comite_pcd_id' => null,
				);
			$result = $this->ApreComiteapre->beforeSave($option);
			$this->assertTrue($result);
		}
	}

?>
