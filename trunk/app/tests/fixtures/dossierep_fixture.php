<?php

	class DossierepFixture extends CakeTestFixture {
		var $name = 'Dossierep';
		var $table = 'dossierseps';
		var $import = array( 'table' => 'dossierseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'seanceep_id' => '1',
				'etapedossierep' => 'etapedossierep',
				'themeep' => 'themeep',
				'created' => null,
				'modified' => null,
			)
		);
	}

?>
