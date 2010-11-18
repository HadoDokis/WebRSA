<?php

	class AmenaglogtFixture extends CakeTestFixture {
		var $name = 'Amenaglogt';
		var $table = 'amenagslogts';
		var $import = array( 'table' => 'amenagslogts', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'typeaidelogement' => null,
				'besoins' => null,
				'montantaide' => null,
			),
		);
	}

?>
