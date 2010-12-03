<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Ep');

	class EpTestCase extends CakeAppModelTestCase {
		function testThemes() {
			$result = $this->Ep->themes();
			//var_dump($result);
		}
	}

?>
