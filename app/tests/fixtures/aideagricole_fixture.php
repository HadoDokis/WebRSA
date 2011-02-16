<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AideagricoleFixture extends CakeAppTestFixture {
		var $name = 'Aideagricole';
		var $table = 'aidesagricoles';
		var $import = array( 'table' => 'aidesagricoles', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'infoagricole_id' => '1',
				'annrefaideagri' => null,
				'libnataideagri' => null,
				'mtaideagri' => null,
			),
		);
	}

?>
