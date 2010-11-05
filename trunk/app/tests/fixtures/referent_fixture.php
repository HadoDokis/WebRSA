<?php

	class ReferentFixture extends CakeTestFixture {
		var $name = 'Referent';
		var $table = 'referents';
		var $import = array( 'table' => 'referents', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'structurereferente_id' => '1',
				'nom' => 'Némard',
				'prenom' => 'Jean',
				'numero_poste' => null,
				'email' => null,
				'qual' => 'M',
				'fonction' => null,
			),
			array(
				'id' => '2',
				'structurereferente_id' => '2',
				'nom' => 'Deufs',
				'prenom' => 'John',
				'numero_poste' => null,
				'email' => null,
				'qual' => 'M',
				'fonction' => null,
			),
		);
	}

?>
