<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Motifreorientep93Fixture extends CakeAppTestFixture {
		var $name = 'Motifreorientep93';
		var $table = 'motifsreorientseps93';
		var $import = array( 'table' => 'motifsreorientseps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'Orientation initiale',
			),
			array(
				'id' => '2',
				'name' => 'Reorientation',
			),
			array(
				'id' => '3',
				'name' => '2nd Reorientation',
			),
		);
	}

?>
