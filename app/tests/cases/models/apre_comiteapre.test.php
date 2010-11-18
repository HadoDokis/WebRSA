<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'ApreComiteapre');

	class ApreComiteapreTestCase extends CakeAppModelTestCase {

		// test function beforeSave
		function testBeforeSave() {
			$result = $this->ApreComiteapre->beforeSave();
			$this->assertTrue($result);

			$result = $this->ApreComiteapre->beforeSave();
			$this->assertEqual($result, true);
		}
	}

?>
