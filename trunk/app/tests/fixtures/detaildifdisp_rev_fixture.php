<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetaildifdispRevFixture extends CakeAppTestFixture {
		var $name = 'DetaildifdispRev';
		var $table = 'detailsdifdisps_revs';
		var $import = array( 'table' => 'detailsdifdisps_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>