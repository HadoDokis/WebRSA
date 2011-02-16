<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailprojproFixture extends CakeAppTestFixture {
		var $name = 'Detailprojpro';
		var $table = 'detailsprojpros';
		var $import = array( 'table' => 'detailsprojpros', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>