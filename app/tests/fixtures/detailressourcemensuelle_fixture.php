<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailressourcemensuelleFixture extends CakeAppTestFixture {
		var $name = 'Detailressourcemensuelle';
		var $table = 'detailsressourcesmensuelles';
		var $import = array( 'table' => 'detailsressourcesmensuelles', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'ressourcemensuelle_id' => 1,
				'natress' => null,
				'mtnatressmen' => null,
				'abaneu' => null,
				'dfpercress' => null,
				'topprevsubsress' => null,
			)
		);
	}

?>
