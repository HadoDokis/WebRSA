<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class SeanceepFixture extends CakeAppTestFixture {
		var $name = 'Seanceep';
		var $table = 'seanceseps';
		var $import = array( 'table' => 'seanceseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '3',
				'identifiant' => 'tretr',
				'name' => 'tert',
				'ep_id' => '2',
				'structurereferente_id' => '74',
				'dateseance' => '2031-01-01',
				'salle' => 'tert',
				'observations' => null,
				'finalisee' => null,
			),
			array(
				'id' => '6',
				'identifiant' => 'EP1.2',
				'name' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
				'ep_id' => '2',
				'structurereferente_id' => '67',
				'dateseance' => '2017-01-01',
				'salle' => 'null',
				'observations' => null,
				'finalisee' => null,
			),
		);
	}

?>
