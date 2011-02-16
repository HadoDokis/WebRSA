<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DecisionparcoursFixture extends CakeAppTestFixture {
		var $name = 'Decisionparcours';
		var $table = 'decisionsparcours';
		var $import = array( 'table' => 'decisionsparcours', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>