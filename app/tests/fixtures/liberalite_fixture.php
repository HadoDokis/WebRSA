<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class LiberaliteFixture extends CakeAppTestFixture {
		var $name = 'Liberalite';
		var $table = 'liberalites';
		var $import = array( 'table' => 'liberalites', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>