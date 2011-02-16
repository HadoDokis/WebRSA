<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DepartementFixture extends CakeAppTestFixture {
		var $name = 'Departement';
		var $table = 'departements';
		var $import = array( 'table' => 'departements', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>