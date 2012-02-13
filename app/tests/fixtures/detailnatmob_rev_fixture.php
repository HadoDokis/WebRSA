<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailnatmobRevFixture extends CakeAppTestFixture {
		var $name = 'DetailnatmobRev';
		var $table = 'detailsnatmobs_revs';
		var $import = array( 'table' => 'detailsnatmobs_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>