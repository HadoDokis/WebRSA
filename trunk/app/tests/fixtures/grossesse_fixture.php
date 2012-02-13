<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class GrossesseFixture extends CakeAppTestFixture {
		var $name = 'Grossesse';
		var $table = 'grossesses';
		var $import = array( 'table' => 'grossesses', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>