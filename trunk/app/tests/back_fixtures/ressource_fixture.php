<?php

	class RessourceFixture extends CakeTestFixture {
		var $name = 'Ressource';
		var $table = 'ressources';
		var $import = array( 'table' => 'ressources', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'topressnul' => '1',
				'mtpersressmenrsa' => '0.00',
				'ddress' => '2005-06-11',
				'dfress' => '2005-08-14',
			),
			array(
				'id' => '2',
				'personne_id' => '3',
				'topressnul' => '0',
				'mtpersressmenrsa' => '1000.00',
				'ddress' => '2006-05-07',
				'dfress' => '2006-07-10',
			),
			array(
				'id' => '3',
				'personne_id' => '5',
				'topressnul' => '0',
				'mtpersressmenrsa' => '22.00',
				'ddress' => '2006-05-07',
				'dfress' => '2006-07-10',
			),
			array(
				'id' => '4',
				'personne_id' => '6',
				'topressnul' => '0',
				'mtpersressmenrsa' => '1200.00',
				'ddress' => '2006-05-07',
				'dfress' => '2006-07-10',
			),
			array(
				'id' => '5',
				'personne_id' => '9',
				'topressnul' => '0',
				'mtpersressmenrsa' => '1750.30',
				'ddress' => '2006-05-07',
				'dfress' => '2006-07-10',
			),
			array(
				'id' => '555',
				'personne_id' => '666',
				'topressnul' => '0',
				'mtpersressmenrsa' => '1000.00',
				'ddress' => '2006-05-07',
				'dfress' => '2006-07-10',
			),
			array(
				'id' => '556',
				'personne_id' => '667',
				'topressnul' => '0',
				'mtpersressmenrsa' => '1000.00',
				'ddress' => '2006-05-07',
				'dfress' => '2006-07-10',
			),
		);
	}

?>
