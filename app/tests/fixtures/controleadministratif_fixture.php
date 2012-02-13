<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ControleadministratifFixture extends CakeAppTestFixture {
		var $name = 'Controleadministratif';
		var $table = 'controlesadministratifs';
		var $import = array( 'table' => 'controlesadministratifs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>