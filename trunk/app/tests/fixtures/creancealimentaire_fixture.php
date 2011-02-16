<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class CreancealimentaireFixture extends CakeAppTestFixture {
		var $name = 'Creancealimentaire';
		var $table = 'creancesalimentaires';
		var $import = array( 'table' => 'creancesalimentaires', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>