<?php

	class EpZonegeographiqueFixture extends CakeTestFixture {
		var $name = 'EpZonegeographique';
		var $table = 'eps_zonesgeographiques';
		var $import = array( 'table' => 'eps_zonesgeographiques', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'ep_id' => '2',
				'zonegeographique_id' => '35',
			),
		);
	}

?>
