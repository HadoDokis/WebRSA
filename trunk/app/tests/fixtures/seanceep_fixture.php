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
				'ep_id' => '1',
				'structurereferente_id' => '7',
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
				'structurereferente_id' => '7',
				'dateseance' => '2017-01-01',
				'salle' => 'null',
				'observations' => null,
				'finalisee' => null,
			),
			array(
				'id' => '9',
				'identifiant' => 'EP2.1',
				'name' => 'CLI 2 Equipe 2.1',
				'ep_id' => '3',
				'structurereferente_id' => '7',
				'dateseance' => '2017-01-01',
				'salle' => null,
				'observations' => null,
				'finalisee' => 'cg',
			),
		);
	}

?>
