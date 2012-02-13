<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class EpZonegeographiqueFixture extends CakeAppTestFixture {
		var $name = 'EpZonegeographique';
		var $table = 'eps_zonesgeographiques';
		var $import = array( 'table' => 'eps_zonesgeographiques', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'ep_id' => '1',
				'zonegeographique_id' => '35',
			),
			array(
				'id' => '2',
				'ep_id' => '2',
				'zonegeographique_id' => '35',
			),
			array(
				'id' => '3',
				'ep_id' => '3',
				'zonegeographique_id' => '35',
			),
		);
	}

?>
