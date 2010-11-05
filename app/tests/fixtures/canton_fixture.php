<?php

	class CantonFixture extends CakeTestFixture {
		var $name = 'Canton';
		var $table = 'cantons';
		var $import = array( 'table' => 'cantons', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'typevoie' => 'R',
				'nomvoie' => 'pignon sur',
				'locaadr' => 'Montpellier',
				'codepos' => 34000,
				'numcomptt' => 12345,
				'canton' => 1,
				'zonegeographique_id' => '1', 
			),
			array(
				'id' => '2',
				'typevoie' => 'A',
				'nomvoie' => 'de saint martin les mines',
				'locaadr' => 'Alès',
				'codepos' => 34000,
				'numcomptt' => 98765,
				'canton' => 2,
				'zonegeographique_id' => '1',
			),
			array(
				'id' => '3',
				'typevoie' => 'P',
				'nomvoie' => 'pigalle',
				'locaadr' => 'Paris',
				'codepos' => 75000,
				'numcomptt' => 36385,
				'canton' => 2,
				'zonegeographique_id' => '1',
			),
		);
	}

?>
