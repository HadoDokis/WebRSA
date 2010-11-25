<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Cui');

	class CuiTestCase extends CakeAppModelTestCase {

		// test function _prepare
		function test_prepare() {
			$result = $this->Cui->_prepare(1);		
			$this->assertFalse($result);

			$result = $this->Cui->_prepare(2);
			$this->assertFalse($result);

			/*
			//FIXME renvoie vrai pour des 'personne_id' innexistants et crée des erreurs php
			$result = $this->Cui->_prepare(1337);		
			$this->assertTrue($result);

			$result = $this->Cui->_prepare(-42);		
			$this->assertTrue($result);
			*/
		}

		/*
		// test function beforevalidate
		function testBeforeValidate() {
			$result = $this->Cui->beforeValidate();
		
		}
		*/
	}

?>

