<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailconfortRevFixture extends CakeAppTestFixture {
		var $name = 'DetailconfortRev';
		var $table = 'detailsconforts_revs';
		var $import = array( 'table' => 'detailsconforts_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>