<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetaildifsocFixture extends CakeAppTestFixture {
		var $name = 'Detaildifsoc';
		var $table = 'detailsdifsocs';
		var $import = array( 'table' => 'detailsdifsocs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dsp_id' => '1',
				'difsoc' => '0404',
				'libautrdifsoc' => null,
			)
		);
	}

?>
