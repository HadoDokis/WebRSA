<?php

	class AcqmatprofFixture extends CakeTestFixture {
		var $name = 'Acqmatprof';
		var $table = 'acqsmatsprofs';
		var $import = array( 'table' => 'acqsmatsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'besoins' => null,
				'montantaide' => null,
			)
		);
	}

?>
