<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Cohorteindu');

	class CohorteinduTestCase extends CakeAppModelTestCase {
		function testBeforeValidate() {
			$result = $this->Cohorteindu->beforeValidate();
			$this->assertFalse($result);
		}

		function testSearch() {
			//$criteresindu = array();
			$result = $this->Cohorteindu->search(null, null, null, null);
			$this->assertTrue($result);
		}
	}
?>
