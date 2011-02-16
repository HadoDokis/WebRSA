<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailaccosocfamFixture extends CakeAppTestFixture {
		var $name = 'Detailaccosocfam';
		var $table = 'detailsaccosocfams';
		var $import = array( 'table' => 'detailsaccosocfams', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dsp_id' => '1',
				'nataccosocfam' => '0411',
				'libautraccosocfam' => null,
			)
		);
	}

?>	
