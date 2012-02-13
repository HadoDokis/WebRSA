<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Critere');

	class CritereTestCase extends CakeAppModelTestCase {
		//test fonction search
		function testSearch() {
			$result = $this->Critere->search(null, null, null, null);
			$this->assertTrue($result);
		}
	}

?>
