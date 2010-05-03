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
										'toppersdrodevorsa' => null,
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
		);
	}

?>