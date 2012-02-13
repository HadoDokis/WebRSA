<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'ApreEtatliquidatif');

	class ApreEtatliquidatifTestCase extends CakeAppModelTestCase {

		// test function beforevalidate()
		function testBeforeValidate() {
			$result = $this->ApreEtatliquidatif->beforeValidate();
			$this->assertTrue($result);

		}
	}

?>
