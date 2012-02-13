<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetaildifsocRevFixture extends CakeAppTestFixture {
		var $name = 'DetaildifsocRev';
		var $table = 'detailsdifsocs_revs';
		var $import = array( 'table' => 'detailsdifsocs_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>