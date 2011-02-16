<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class RessourcemensuelleFixture extends CakeAppTestFixture {
		var $name = 'Ressourcemensuelle';
		var $table = 'ressourcesmensuelles';
		var $import = array( 'table' => 'ressourcesmensuelles', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'ressource_id' => '1',
				'moisress' => null,
				'nbheumentra' => null,
				'mtabaneu' => null,
			),
			array(
				'id' => '2',
				'ressource_id' => '2',
				'moisress' => null,
				'nbheumentra' => null,
				'mtabaneu' => null,
			),
		);
	}

?>
