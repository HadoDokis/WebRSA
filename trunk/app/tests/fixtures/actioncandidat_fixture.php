<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ActioncandidatFixture extends CakeAppTestFixture {
		var $name = 'Actioncandidat';
		var $table = 'actionscandidats';
		var $import = array( 'table' => 'actionscandidats', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'intitule' => 'intitulé',
				'code' => null,
			),
		);
	}

?>
