<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ReferentFixture extends CakeAppTestFixture {
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
			array(
				'id' => '3',
				'structurereferente_id' => '7',
				'nom' => 'Lereferent',
				'prenom' => 'Dddddd',
				'numero_poste' => null,
				'email' => null,
				'qual' => 'M',
				'fonction' => null,
			)
		);
	}

?>
