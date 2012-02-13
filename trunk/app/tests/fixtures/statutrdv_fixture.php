<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class StatutrdvFixture extends CakeAppTestFixture {
		var $name = 'Statutrdv';
		var $table = 'statutsrdvs';
		var $import = array( 'table' => 'statutsrdvs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>