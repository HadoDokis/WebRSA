<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TempradiationFixture extends CakeAppTestFixture {
		var $name = 'Tempradiation';
		var $table = 'tempradiations';
		var $import = array( 'table' => 'tempradiations', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>