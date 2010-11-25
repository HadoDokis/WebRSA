<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Cohorteci');

	class CohorteciTestCase extends CakeAppModelTestCase {
		// test fonction search
		function testSearch() {
		$result = $this->Cohorteci->search();
		var_dump($result);
		}
	}

?>
