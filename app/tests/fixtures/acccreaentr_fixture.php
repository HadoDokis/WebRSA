<?php

	class AcccreaentrFixture extends CakeTestFixture {
		var $name = 'Acccreaentr';
		var $table = 'accscreaentr';
		var $import = array( 'table' => 'accscreaentr', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'nacre' => null,
				'microcredit' => null,
				'projet' => null,
				'montantaide' => null,
			),
		);
	}

?>
