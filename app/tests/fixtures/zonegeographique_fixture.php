<?php

	class ZonegeographiqueFixture extends CakeTestFixture {
		var $name = 'Zonegeographique';
		var $table = 'zonesgeographiques';
		var $import = array( 'table' => 'zonesgeographiques', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'codeinsee' => '34000',
				'libelle' => 'Pole Montpellier-Nord',
			),
			array(
				'id' => '2',
				'codeinsee' => '34070',
				'libelle' => 'Pole Montpellier Sud-Est',
			),
			array(
				'id' => '3',
				'codeinsee' => '34080',
				'libelle' => 'Pole Montpellier Ouest',
			),
		);
	}

?>