<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Cohorteci');

	class CohorteciTestCase extends CakeAppModelTestCase {
		// test fonction search
		function testSearch() {
		$result = $this->Cohorteci->search(null, 34000, null, null, null);
		$this->assertTrue($result);

		$result = $this->Cohorteci->search('Decisionci::nonvalide', 34080, null, null, null);
		$this->assertTrue($result);

		$result = $this->Cohorteci->search('Decisionci::enattente', 34080, null, null, null);
		$this->assertTrue($result);

		$result = $this->Cohorteci->search('Decisionci::valides', 34080, null, null, null);
		$this->assertTrue($result);
		}
	}

?>
