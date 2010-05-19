<?php

	class AdressefoyerFixture extends CakeTestFixture {
		var $name = 'Adressefoyer';
		var $table = 'adresses_foyers';
		var $import = array( 'table' => 'adresses_foyers', 'connection' => 'default', 'records' => false);
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
		);
	}

?>
