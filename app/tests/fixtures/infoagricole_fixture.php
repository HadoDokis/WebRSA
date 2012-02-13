<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class InfoagricoleFixture extends CakeAppTestFixture {
		var $name = 'Infoagricole';
		var $table = 'infosagricoles';
		var $import = array( 'table' => 'infosagricoles', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>