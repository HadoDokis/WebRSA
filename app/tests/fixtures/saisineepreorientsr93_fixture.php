<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Saisineepreorientsr93Fixture extends CakeAppTestFixture {
		var $name = 'Saisineepreorientsr93';
		var $table = 'saisinesepsreorientsrs93';
		var $import = array( 'table' => 'saisinesepsreorientsrs93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossierep_id' => '1',
				'orientstruct_id' => '1001',
				'typeorient_id' => '5',
				'structurereferente_id' => '7',
				'datedemande' => '2010-01-01',
				'referent_id' => null,
				'motifreorient_id' => '1',
				'commentaire' => null,
				'accordaccueil' => '1',
				'desaccordaccueil' => null,
				'accordallocataire' => '1',
				'urgent' => '0',
				'created' => null,
				'modified' => null,
			),
			array(
				'id' => '2',
				'dossierep_id' => '2',
				'orientstruct_id' => '2002',
				'typeorient_id' => '5',
				'structurereferente_id' => '7',
				'datedemande' => '2010-02-01',
				'referent_id' => null,
				'motifreorient_id' => '2',
				'commentaire' => null,
				'accordaccueil' => '1',
				'desaccordaccueil' => null,
				'accordallocataire' => '1',
				'urgent' => '0',
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
