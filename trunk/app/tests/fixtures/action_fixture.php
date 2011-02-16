<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ActionFixture extends CakeAppTestFixture {
		var $name = 'Action';
		var $table = 'actions';
		var $import = array( 'table' => 'actions', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'typeaction_id' => '1',
				'code' => null,
				'libelle' => 'libellé',
			),
			array(
				'id' => '2',
				'typeaction_id' => '1',
				'code' => null,
				'libelle' => 'libellé',
			),
		);
	}

?>
