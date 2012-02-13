<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailressourcemensuelleRessourcemensuelleFixture extends CakeAppTestFixture {
		var $name = 'DetailressourcemensuelleRessourcemensuelle';
		var $table = 'detailsressourcesmensuelles_ressourcesmensuelles';
		var $import = array( 'table' => 'detailsressourcesmensuelles_ressourcesmensuelles', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'detailressourcemensuelle_id' => '1',
				'ressourcemensuelle_id' => '1',
				'id' => '1',
			),
		);
	}

?>
