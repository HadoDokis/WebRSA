<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Nvsrepreorientsr93Fixture extends CakeAppTestFixture {
		var $name = 'Nvsrepreorientsr93';
		var $table = 'nvsrsepsreorientsrs93';
		var $import = array( 'table' => 'nvsrsepsreorientsrs93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'saisineepreorientsr93_id' => '1',
				'etape' => 'cg',
				'decision' => 'accepte',
				'typeorient_id' => '5',
				'structurereferente_id' => '7',
				'referent_id' => null,
				'commentaire' => null,
				'created' => null,
				'modified' => null,
			),
			array(
				'id' => '2',
				'saisineepreorientsr93_id' => '2',
				'etape' => 'cg',
				'decision' => 'accepte',
				'typeorient_id' => '5',
				'structurereferente_id' => '7',
				'referent_id' => null,
				'commentaire' => null,
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
