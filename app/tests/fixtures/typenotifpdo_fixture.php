<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TypenotifpdoFixture extends CakeAppTestFixture {
		var $name = 'Typenotifpdo';
		var $table = 'typesnotifspdos';
		var $import = array( 'table' => 'typesnotifspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>