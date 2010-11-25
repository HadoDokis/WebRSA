<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Criterecui');

	class CriterecuiTestCase extends CakeAppModelTestCase {
		// test fonction search
		function testSearch() {
			$result = $this->Criterecui->search(null, null, null, null);
			$this->assertTrue($result);
		}
	}

?>
