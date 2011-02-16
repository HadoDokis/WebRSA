<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailmoytransRevFixture extends CakeAppTestFixture {
		var $name = 'DetailmoytransRev';
		var $table = 'detailsmoytrans_revs';
		var $import = array( 'table' => 'detailsmoytrans_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>