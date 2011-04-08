<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Typeaideapre66');

	class Typeaideapre66TestCase extends CakeAppModelTestCase {

		function testListOptions() {
			$result = $this->Typeaideapre66->listOptions();
			$expected = array('1_1' => 'typesaideapr66name');
			$this->assertEqual($expected, $result);
		}
	}

?>
