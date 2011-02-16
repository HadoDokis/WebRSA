<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailconfortFixture extends CakeAppTestFixture {
		var $name = 'Detailconfort';
		var $table = 'detailsconforts';
		var $import = array( 'table' => 'detailsconforts', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>