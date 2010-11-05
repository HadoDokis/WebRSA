<?php

	class PersonneReferentFixture extends CakeTestFixture {
		var $name = 'PersonneReferent';
		var $table = 'personnes_referents';
		var $import = array( 'table' => 'personnes_referents', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'referent_id' => '1',
				'dddesignation' => null,
				'dfdesignation' => null,
				'structurereferente_id' => '1',
			),
			array(
				'id' => '2',
				'personne_id' => '2',
				'referent_id' => '1',
				'dddesignation' => null,
				'dfdesignation' => null,
				'structurereferente_id' => '1',
			),
			array(
				'id' => '3',
				'personne_id' => '3',
				'referent_id' => '1',
				'dddesignation' => null,
				'dfdesignation' => null,
				'structurereferente_id' => '1',
			),
		);
	}

?>
