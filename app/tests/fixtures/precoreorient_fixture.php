<?php

	class PrecoreorientFixture extends CakeTestFixture {
		var $name = 'Precoreorient';
		var $table = 'precosreorients';
		var $import = array( 'table' => 'precosreorients', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'demandereorient_id' => '1',
				'rolereorient' => 'roleorient?',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'referent_id' => '1',
				'accord' => null,
				'commentaire' => null,
				'created' => null,
				'dtconcertation' => null,
			)
		);
	}

?>
