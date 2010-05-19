<?php

	class PrestationFixture extends CakeTestFixture {
		var $name = 'Prestation';
		var $table = 'prestations';
		var $import = array( 'table' => 'prestations', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'personne_id' => '1',
				'natprest' => 'RSA',
				'rolepers' => 'DEM',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '1',
			),
			array(
				'personne_id' => '2',
				'natprest' => 'RSA',
				'rolepers' => 'ENF',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '2',
			),
			array(
				'personne_id' => '3',
				'natprest' => 'RSA',
				'rolepers' => 'DEM',
				'topchapers' => null,
				'toppersdrodevorsa' => true,
				'id' => '3',
			),
			array(
				'personne_id' => '4',
				'natprest' => 'RSA',
				'rolepers' => 'CJT',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '4',
			),
			array(
				'personne_id' => '5',
				'natprest' => 'RSA',
				'rolepers' => 'DEM',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '5',
			),
			array(
				'personne_id' => '6',
				'natprest' => 'RSA',
				'rolepers' => 'DEM',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '6',
			),
			array(
				'personne_id' => '7',
				'natprest' => 'RSA',
				'rolepers' => 'CJT',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '7',
			),
			array(
				'personne_id' => '8',
				'natprest' => 'RSA',
				'rolepers' => 'ENF',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '8',
			),
			array(
				'personne_id' => '9',
				'natprest' => 'RSA',
				'rolepers' => 'DEM',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '9',
			),
			array(
				'personne_id' => '10',
				'natprest' => 'RSA',
				'rolepers' => 'CJT',
				'topchapers' => null,
				'toppersdrodevorsa' => true,
				'id' => '10',
			),
			array(
				'personne_id' => '11',
				'natprest' => 'RSA',
				'rolepers' => 'ENF',
				'topchapers' => null,
				'toppersdrodevorsa' => null,
				'id' => '11',
			),
		);
	}

?>
