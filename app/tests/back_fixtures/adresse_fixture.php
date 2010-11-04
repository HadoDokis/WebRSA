<?php

	class AdresseFixture extends CakeTestFixture {
		var $name = 'Adresse';
		var $table = 'adresses';
		var $import = array( 'table' => 'adresses', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'numvoie' => null,
				'typevoie' => 'R',
				'nomvoie' => 'de lilas',
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'numcomrat' => '     ',
				'numcomptt' => 'ighr8',
				'codepos' => '23458',
				'locaadr' => 'Mont de Marsan',
				'pays' => 'FRA',
				'canton' => null,
			),
			array(
				'id' => '2',
				'numvoie' => null,
				'typevoie' => 'A',
				'nomvoie' => 'de la lutte finale',
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'numcomrat' => '     ',
				'numcomptt' => 'pokf2',
				'codepos' => '12345',
				'locaadr' => 'Montpellier',
				'pays' => 'FRA',
				'canton' => null,
			),
		);
	}

?>
