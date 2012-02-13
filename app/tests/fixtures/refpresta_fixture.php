<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class RefprestaFixture extends CakeAppTestFixture {
		var $name = 'Refpresta';
		var $table = 'refsprestas';
		var $import = array( 'table' => 'refsprestas', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>