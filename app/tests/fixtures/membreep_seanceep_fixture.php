<?php

	class MembreepSeanceepFixture extends CakeTestFixture {
		var $name = 'MembreepSeanceep';
		var $table = 'membreseps_seanceseps';
		var $import = array( 'table' => 'membreseps_seanceseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '8',
				'seanceep_id' => '6',
				'membreep_id' => '1',
				'suppleant' => '0',
				'suppleant_id' => null,
				'reponse' => 'confirme',
				'presence' => null,
			),
		);
	}

?>
