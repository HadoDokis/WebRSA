<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class UserZonegeographiqueFixture extends CakeAppTestFixture {
		var $name = 'UserZonegeographique';
		var $table = 'users_zonesgeographiques';
		var $import = array( 'table' => 'users_zonesgeographiques', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'user_id' => '3',
				'zonegeographique_id' => '1',
				'id' => '1',
			),
		);
	}

?>
