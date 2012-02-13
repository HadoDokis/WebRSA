<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'ApreComiteapre');

	class ApreComiteapreTestCase extends CakeAppModelTestCase {

		// test function beforeSave
		function testBeforeSave() {
			$this->ApreComiteapre->data = array(
				'ApreComiteapre' => array(
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
				),
			);
			$option = null;
			$result = $this->ApreComiteapre->beforeSave($option);
			$this->assertTrue($result);

			$this->ApreComiteapre->data = array(
				'ApreComiteapre' => array(
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
				),
			);
			$option = null;
			$result = $this->ApreComiteapre->beforeSave($option);
			$this->assertTrue($result);
		}
	}

?>
