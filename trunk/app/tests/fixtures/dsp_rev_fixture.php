<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DspRevFixture extends CakeAppTestFixture {
		var $name = 'DspRev';
		var $table = 'dsps_revs';
		var $import = array( 'table' => 'dsps_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>