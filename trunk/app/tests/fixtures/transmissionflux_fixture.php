<?php

	class TransmissionfluxFixture extends CakeTestFixture {
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
