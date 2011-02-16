<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailnatmobFixture extends CakeAppTestFixture {
		var $name = 'Detailnatmob';
		var $table = 'detailsnatmobs';
		var $import = array( 'table' => 'detailsnatmobs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>