<?php

	class DossiercafFixture extends CakeTestFixture {
		var $name = 'Dossiercaf';
		var $table = 'dossierscaf';
		var $import = array( 'table' => 'dossierscaf', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'ddratdos' => null,
				'dfratdos' => null,
				'toprespdos' => null,
				'numdemrsaprece' => null,
			)
		);
	}

?>
