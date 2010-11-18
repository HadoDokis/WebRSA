<?php

	class ActprofFixture extends CakeTestFixture {
		var $name = 'Actprof';
		var $table = 'actsprofs';
		var $import = array( 'table' => 'actsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'typecontratact' => null,
				'ddconvention' => null,
				'dfconvention' => null,
				'intituleformation' => null,
				'ddform' => null,
				'dfform' => null,
				'dureeform' => null,
				'modevalidation' => null,
				'coutform' => null,
				'cofinanceurs' => null,
				'montantaide' => null,
				'tiersprestataireapre_id' => '1',
			),
		);
	}

?>
