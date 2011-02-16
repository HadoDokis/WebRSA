<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class RegroupementepFixture extends CakeAppTestFixture {
		var $name = 'Regroupementep';
		var $table = 'regroupementseps';
		var $import = array( 'table' => 'regroupementseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'CLI 1',
,
			),
			array(
				'id' => '2',
				'name' => 'CLI 2',
			),
			array(
				'id' => '5',
				'name' => 'CLI 3',
			),
			array(
				'id' => '6',
				'name' => 'CLI 4',
			),
			array(
				'id' => '7',
				'name' => 'CLI 5',
			),
			array(
				'id' => '8',
				'name' => ' CLI 6',
			),
		);
	}

?>
