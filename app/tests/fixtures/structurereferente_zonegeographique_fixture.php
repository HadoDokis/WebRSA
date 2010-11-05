<?php

	class StructurereferenteZonegeographiqueFixture extends CakeTestFixture {
		var $name = 'StructurereferenteZonegeographique';
		var $table = 'structuresreferentes_zonesgeographiques';
		var $import = array( 'table' => 'structuresreferentes_zonesgeographiques', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'structurereferente_id' => '3',
				'zonegeographique_id' => '1',
				'id' => '1',
			)
		);
	}

?>
