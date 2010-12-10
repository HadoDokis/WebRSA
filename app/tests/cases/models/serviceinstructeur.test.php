<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Serviceinstructeur');

	class ServiceinstructeurTestCase extends CakeAppModelTestCase {

		function testListOptions() {
			$result = $this->Serviceinstructeur->listOptions();
			$expected = array(
				'1' => 'Service 1',
				'2' => 'Service 2',
			);
			$this->assertEqual($expected, $result);
		}

		//prepare( $type, $params = array())
		function testPrepare( $type, $params = array()) {
			$type = 'lol';//$this->Serviceinstructeur->find();
			$params = null;
			$result = $this->Serviceinstructeur->prepare($type, $params);
		}
	}

?>
