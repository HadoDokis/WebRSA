<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Sanctionep58Fixture extends CakeAppTestFixture {
		var $name = 'Sanctionep58';
		var $table = 'sanctionseps58';
		var $import = array( 'table' => 'sanctionseps58', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossierep_id' => '1',
				'origine' => 'radiepe',
				'listesanctionep58_id' => '1',
				'commentaire' => null,
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
