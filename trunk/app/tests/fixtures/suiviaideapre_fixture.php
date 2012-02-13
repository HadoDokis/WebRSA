<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class SuiviaideapreFixture extends CakeAppTestFixture {
		var $name = 'Suiviaideapre';
		var $table = 'suivisaidesapres';
		var $import = array( 'table' => 'suivisaidesapres', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>