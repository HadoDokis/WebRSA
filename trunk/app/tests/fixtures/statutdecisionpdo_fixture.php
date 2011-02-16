<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class StatutdecisionpdoFixture extends CakeAppTestFixture {
		var $name = 'Statutdecisionpdo';
		var $table = 'statutsdecisionspdos';
		var $import = array( 'table' => 'statutsdecisionspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>