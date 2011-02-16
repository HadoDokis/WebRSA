<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetaildiflogRevFixture extends CakeAppTestFixture {
		var $name = 'DetaildiflogRev';
		var $table = 'detailsdiflogs_revs';
		var $import = array( 'table' => 'detailsdiflogs_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>