<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailprojproFixture extends CakeAppTestFixture {
		var $name = 'Detailprojpro';
		var $table = 'detailsprojpros';
		var $import = array( 'table' => 'detailsprojpros', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dsp_id' => '1',
				'projpro' => '2201',
				'libautrprojpro' => null,
			),
			array(
				'id' => '2',
				'dsp_id' => '2',
				'projpro' => '2202',
				'libautrprojpro' => null,
			),
		);
	}

?>
