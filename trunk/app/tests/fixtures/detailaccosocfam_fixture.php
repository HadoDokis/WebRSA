<?php

	class DetailaccosocfamFixture extends CakeTestFixture {
		var $name = 'Detailaccosocfam';
		var $table = 'detailsaccosocfams';
		var $import = array( 'table' => 'detailsaccosocfams', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dsp_id' => '1',
				'nataccosocfam' => 'notnull',
				'libautraccosocfam' => null,
			)
		);
	}

?>	
