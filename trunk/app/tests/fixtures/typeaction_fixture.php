<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TypeactionFixture extends CakeAppTestFixture {
		var $name = 'Typeaction';
		var $table = 'typesactions';
		var $import = array( 'table' => 'typesactions', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'Facilités offertes',
			),
			array(
				'id' => '2',
				'libelle' => 'Autonomie sociale',
			),
			array(
				'id' => '3',
				'libelle' => 'Logement',
			),
			array(
				'id' => '4',
				'libelle' => 'Insertion professionnelle',
			),
			array(
				'id' => '5',
				'libelle' => 'Emploi',
			),
		);
	}

?>
