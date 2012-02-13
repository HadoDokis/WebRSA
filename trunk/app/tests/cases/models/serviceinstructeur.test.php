<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Serviceinstructeur');

	class ServiceinstructeurTestCase extends CakeAppModelTestCase {

		function testListOptions() {
			$result = $this->Serviceinstructeur->listOptions();
			$expected = array(
				'1' => 'Service 1',
				'2' => 'Service 2',
				'3' => 'CCAS Saint Denis',
			);
			$this->assertEqual($expected, $result);
		}

		//prepare( $type, $params = array())
		function testPrepare( $type, $params = array()) {
			$type = null;//$this->Serviceinstructeur->find();
			$params = null;
			$result = $this->Serviceinstructeur->prepare($type, $params);
			$this->assertFalse($result);
		}
/*
		function testQueryDataError() {
			$model = null;
			$querydata = null;
			$this->assertFalse($this->Serviceinstructeur->_queryDataError(&$model, $querydata));
		}
*/
		function testSqrechercheErrors() {
			$condition = null;
			$result = $this->Serviceinstructeur->sqrechercheErrors($condition);
		}

		function testValidateSqrecherche() {
			$check = null;
			$result = $this->Serviceinstructeur->ValidateSqrecherche($check);
			$this->assertFalse($result);
		}

	}

?>
