<?php

	class CreanceFixture extends CakeTestFixture {
		var $name = 'Creance';
		var $table = 'creances';
		var $import = array( 'table' => 'creances', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dtimplcre'  => null,
				'natcre' => null,
				'rgcre' => null,
				'motiindu' => null,
				'oriindu' => null,
				'respindu' => null,
				'ddregucre' => null,
				'dfregucre' => null,
				'dtdercredcretrans' => null,
				'mtinicre' => null,
				'foyer_id' => '1',
			)
		);
	}

?>
