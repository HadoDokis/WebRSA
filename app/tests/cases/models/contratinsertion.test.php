<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Contratinsertion');

	class ContratinsertionTestCase extends CakeAppModelTestCase {

		// test fonction beforesave()
		function testBeforesave() {
			$result = $this->Contratinsertion->beforesave($option=array());
			$this->assertTrue($result);
		}

		// test fonction valider()
		function testValider() {
			$data = null;
			$result = $this->Contratinsertion->valider($data);
			$this->assertFalse($result);
		}
 
		// test fonction aftersave()
		function testAftersave() {
			$result = $this->Contratinsertion->aftersave();
			$this->assertFalse($result);
		}

	}

?>
