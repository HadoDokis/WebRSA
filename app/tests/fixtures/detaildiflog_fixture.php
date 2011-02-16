<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetaildiflogFixture extends CakeAppTestFixture {
		var $name = 'Detaildiflog';
		var $table = 'detailsdiflogs';
		var $import = array( 'table' => 'detailsdiflogs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>