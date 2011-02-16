<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ZonegeographiqueFixture extends CakeAppTestFixture {
		var $name = 'Zonegeographique';
		var $table = 'zonesgeographiques';
		var $import = array( 'table' => 'zonesgeographiques', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'codeinsee' => '34090',
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
			array(
				'id' => '35',
				'codeinsee' => '93066',
				'libelle' => 'SAINT-DENIS',
			),
		);
	}

?>
