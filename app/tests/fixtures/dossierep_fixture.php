<?php

	class DossierepFixture extends CakeTestFixture {
		var $name = 'Dossierep';
		var $table = 'dossierseps';
		var $import = array( 'table' => 'dossierseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1001',
				'seanceep_id' => '3',
				'etapedossierep' => 'cree',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			),
			array(
				'id' => '2',
				'personne_id' => '1002',
				'seanceep_id' => '6',
				'etapedossierep' => 'cree',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
