<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetaildifdispFixture extends CakeAppTestFixture {
		var $name = 'Detaildifdisp';
		var $table = 'detailsdifdisps';
		var $import = array( 'table' => 'detailsdifdisps', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>