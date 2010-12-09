<?php

	class DetaildifsocFixture extends CakeTestFixture {
		var $name = 'Detaildifsoc';
		var $table = 'detailsdifsocs';
		var $import = array( 'table' => 'detailsdifsocs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dsp_id' => '1',
				'difsoc' => 'difspc',
				'libautrdifsoc' => null,
			)
		);
	}

?>
