<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class StatutpdoFixture extends CakeAppTestFixture {
		var $name = 'Statutpdo';
		var $table = 'statutspdos';
		var $import = array( 'table' => 'statutspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>