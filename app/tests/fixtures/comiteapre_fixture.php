<?php

	class ComiteapreFixture extends CakeTestFixture {
		var $name = 'Comiteapre';
		var $table = 'comitesapres';
		var $import = array( 'table' => 'comitesapres', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'datecomite' => null,
				'heurecomite' => null,
				'lieucomite' => null,
				'intitulecomite' => null,
				'observationcomite' => null,
			),
			array(
				'id' => '2',
				'datecomite' => null,
				'heurecomite' => null,
				'lieucomite' => null,
				'intitulecomite' => null,
				'observationcomite' => null,
			),
		);
	}

?>
