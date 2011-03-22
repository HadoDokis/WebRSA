<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Listesanctionep58Fixture extends CakeAppTestFixture {
		var $name = 'Listesanctionep58';
		var $table = 'listesanctionseps58';
		var $import = array( 'table' => 'listesanctionseps58', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'rand' => '1',
				'sanction' => 'sanction1',
				'duree' => '1',
			),
		);
	}

?>
