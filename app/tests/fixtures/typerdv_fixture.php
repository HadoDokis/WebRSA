<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TyperdvFixture extends CakeAppTestFixture {
		var $name = 'Typerdv';
		var $table = 'typesrdv';
		var $import = array( 'table' => 'typesrdv', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>