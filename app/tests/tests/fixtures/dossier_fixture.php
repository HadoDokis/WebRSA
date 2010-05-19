<?php

	class DossierFixture extends CakeTestFixture {

		var $name = 'Dossier';

		var $import = array('table' => 'dossiers_rsa');

		var $table = 'dossiers_rsa';

		var $records = array (
			array (
				'id' => 3,
				'numdemrsa' => 12345678901,
				'dtdemrsa' => '12/12/2009'
			)
		);

	}

?>
