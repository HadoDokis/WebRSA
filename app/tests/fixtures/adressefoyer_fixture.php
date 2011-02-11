<?php

	class AdressefoyerFixture extends CakeTestFixture {
		var $name = 'Adressefoyer';
		var $table = 'adressesfoyers';
		var $import = array( 'table' => 'adressesfoyers', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'adresse_id' => '1',
				'foyer_id' => '1',
				'rgadr' => '01',
				'dtemm' => null,
				'typeadr' => 'P',
			),
			array(
				'id' => '2',
				'adresse_id' => '2',
				'foyer_id' => '2',
				'rgadr' => '01',
				'dtemm' => null,
				'typeadr' => 'P',
			),
			array(
				'id' => '1001',
				'adresse_id' => '1001',
				'foyer_id' => '1001',
				'rgadr' => '01',
				'dtemm' => null,
				'typeadr' => 'P',
			),
			array(
				'id' => '2002',
				'adresse_id' => '2002',
				'foyer_id' => '2002',
				'rgadr' => '01',
				'dtemm' => null,
				'typeadr' => 'P',
			),
			array(
				'id' => '3003',
				'adresse_id' => '3003',
				'foyer_id' => '3003',
				'rgadr' => '01',
				'dtemm' => null,
				'typeadr' => 'P',
			),
			array(
				'id' => '4004',
				'adresse_id' => '4004',
				'foyer_id' => '4004',
				'rgadr' => '01',
				'dtemm' => null,
				'typeadr' => 'P',
			),
		);
	}

?>
