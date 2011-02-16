<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailmoytransFixture extends CakeAppTestFixture {
		var $name = 'Detailmoytrans';
		var $table = 'detailsmoytrans';
		var $import = array( 'table' => 'detailsmoytrans', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>