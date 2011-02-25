<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DossierepFixture extends CakeAppTestFixture {
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
				'personne_id' => '2002',
				'seanceep_id' => '6',
				'etapedossierep' => 'cree',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			),
			array(
				'id' => '3',
				'personne_id' => '3003',
				'seanceep_id' => '9',
				'etapedossierep' => 'traite',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
