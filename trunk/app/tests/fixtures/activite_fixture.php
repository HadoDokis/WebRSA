<?php

	class ActiviteFixture extends CakeTestFixture {
		var $name = 'Activite';
		var $table = 'activites';
		var $import = array( 'table' => 'activites', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'reg' => null,
				'act' => null,
				'paysact' => null,
				'ddact' => null,
				'dfact' => null,
				'natcontrtra' => null,
				'topcondadmeti' => null,
				'hauremuscmic' => null,
			)
		);
	}

?>
