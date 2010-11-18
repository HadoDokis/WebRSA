<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Calculdroitrsa');

	class CalculdroitrsaTestCase extends CakeAppModelTestCase {

		function testDossierId() {
			$this->assertEqual(1,$this->Calculdroitrsa->dossierId(1));
			$this->assertEqual(1,$this->Calculdroitrsa->dossierId(2));

			$this->assertNull($this->Calculdroitrsa->dossierId(1337));
			$this->assertNull($this->Calculdroitrsa->dossierId(-42));
		}

	}

?>
