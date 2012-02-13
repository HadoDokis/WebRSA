<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Permanence');

	class PermanenceTestCase extends CakeAppModelTestCase {
		//test fonction listoption()
		function testListOptions() {
			$result = $this->Permanence->listOptions();
			$expected = array(
					'1_1' => 'libpermanence?',
					);
			$this->assertEqual($expected, $result);
		}
	}

?>
