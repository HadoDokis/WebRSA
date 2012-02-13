<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Apre66');

	class Apre66TestCase extends CakeAppModelTestCase {
		function testDossierId() {
		$result = $this->Apre66->dossierId(1);
		$this->assertEqual(1, $result);
		$result = $this->Apre66->dossierId(2);
		$this->assertEqual(3, $result);
		$result = $this->Apre66->dossierId(1337);
		$this->assertFalse($result);
		}
	}

?>
