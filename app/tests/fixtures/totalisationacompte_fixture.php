<?php

	class TotalisationacompteFixture extends CakeTestFixture {
		var $name = 'Totalisationacompte';
		var $table = 'totalisationsacomptes';
		var $import = array( 'table' => 'totalisationsacomptes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'identificationflux_id' => '1',
				'type_totalisation' => null,
				'mttotsoclrsa' => null,
				'mttotsoclmajorsa' => null,
				'mttotlocalrsa' => null,
				'mttotrsa' => null,
			),
		);
	}

?>
