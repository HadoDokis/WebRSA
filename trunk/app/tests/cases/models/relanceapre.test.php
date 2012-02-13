<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Relanceapre');

	class RelanceapreTestCase extends CakeAppModelTestCase {

		function testAfterFind() {
			$results = $this->Relanceapre->find();
			$primary = null;
			$result = $this->Relanceapre->afterfind( $results, $primary);
			$this->assertEqual($results, $result);
		}
	}

?>
