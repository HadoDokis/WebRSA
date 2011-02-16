<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TempinscriptionFixture extends CakeAppTestFixture {
		var $name = 'Tempinscription';
		var $table = 'tempinscriptions';
		var $import = array( 'table' => 'tempinscriptions', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>