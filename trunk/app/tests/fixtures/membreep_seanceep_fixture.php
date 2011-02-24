<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class MembreepSeanceepFixture extends CakeAppTestFixture {
		var $name = 'MembreepSeanceep';
		var $table = 'membreseps_seanceseps';
		var $import = array( 'table' => 'membreseps_seanceseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'seanceep_id' => '3',
				'membreep_id' => '1',
				'suppleant' => '0',
				'suppleant_id' => null,
				'reponse' => 'confirme',
				'presence' => null,
			),
			array(
				'id' => '2',
				'seanceep_id' => '6',
				'membreep_id' => '2',
				'suppleant' => '0',
				'suppleant_id' => null,
				'reponse' => 'confirme',
				'presence' => null,
			),
		);
	}

?>
