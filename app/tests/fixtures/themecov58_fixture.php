<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Themecov58Fixture extends CakeAppTestFixture {
		var $name = 'Themecov58';
		var $table = 'themescovs58';
		var $import = array( 'table' => 'themescovs58', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'themecov58n1',
			),
			array(
				'id' => '2',
				'name' => 'themecov58n2',
			),
		);
	}

?>
