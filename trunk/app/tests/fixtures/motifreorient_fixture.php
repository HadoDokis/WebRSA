<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class MotifreorientFixture extends CakeAppTestFixture {
		var $name = 'Motifreorient';
		var $table = 'motifsreorients';
		var $import = array( 'table' => 'motifsreorients', 'connection' => 'default', 'records' => false);
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
