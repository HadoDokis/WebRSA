<?php

	class UserZonegeographiqueFixture extends CakeTestFixture {
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
