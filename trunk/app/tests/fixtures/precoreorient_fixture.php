<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PrecoreorientFixture extends CakeAppTestFixture {
		var $name = 'Precoreorient';
		var $table = 'precosreorients';
		var $import = array( 'table' => 'precosreorients', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'demandereorient_id' => '1',
				'rolereorient' => 'referent',
				'typeorient_id' => '1',
				'structurereferente_id' => '1',
				'referent_id' => '1',
				'accord' => 0,
				'commentaire' => null,
				'created' => null,
				'dtconcertation' => null,
			)
		);
	}

?>
