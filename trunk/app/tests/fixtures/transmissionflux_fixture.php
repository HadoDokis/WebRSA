<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TransmissionfluxFixture extends CakeAppTestFixture {
		var $name = 'Transmissionflux';
		var $table = 'transmissionsflux';
		var $import = array( 'table' => 'transmissionsflux', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'identificationflux_id' => 1,
				'nbtotdemrsatransm' => null,
				'nbtotdosrsatransmano' => null,
				'nbtotdosrsatransm' => null,
			)
		);
	}

?>
