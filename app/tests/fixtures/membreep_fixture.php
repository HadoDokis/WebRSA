<?php

	class MembreepFixture extends CakeTestFixture {
		var $name = 'Membreep';
		var $table = 'membreseps';
		var $import = array( 'table' => 'membreseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'fonctionmembreep_id' => '1',
				'qual' => 'Mlle.',
				'nom' => 'Dupont',
				'prenom' => 'Anne',
				'tel' => null,
				'mail' => null,
				'suppleant_id' => null,
			),
			array(
				'id' => '2',
				'fonctionmembreep_id' => '1',
				'qual' => 'M.',
				'nom' => 'Martin',
				'prenom' => 'Pierre',
				'tel' => null,
				'mail' => null,
				'suppleant_id' => null,
			),
		);
	}

?>
