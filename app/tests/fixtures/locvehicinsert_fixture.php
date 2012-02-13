<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class LocvehicinsertFixture extends CakeAppTestFixture {
		var $name = 'Locvehicinsert';
		var $table = 'locsvehicinsert';
		var $import = array( 'table' => 'locsvehicinsert', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>