<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class SuspensiondroitFixture extends CakeAppTestFixture {
		var $name = 'Suspensiondroit';
		var $table = 'suspensionsdroits';
		var $import = array( 'table' => 'suspensionsdroits', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>