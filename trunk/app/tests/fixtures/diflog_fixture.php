<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DiflogFixture extends CakeAppTestFixture {
		var $name = 'Diflog';
		var $table = 'diflogs';
		var $import = array( 'table' => 'diflogs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'code' => '1001',
				'name' => 'Pas de difficultés',
			),
			array(
				'id' => '2',
				'code' => '1002',
				'name' => 'Impayés de loyer ou de remboursement',
			),
			array(
				'id' => '3',
				'code' => '1003',
				'name' => 'Problèmes financiers',
			),
			array(
				'id' => '4',
				'code' => '1004',
				'name' => 'Qualité du logement (insalubrité, indépendence)',
			),
			array(
				'id' => '5',
				'code' => '1005',
				'name' => 'Qualité de l\'environnement (isolement, absence de transport collectif)',
			),
			array(
				'id' => '6',
				'code' => '1006',
				'name' => 'Fin de bail, expulsion',
			),
			array(
				'id' => '7',
				'code' => '1007',
				'name' => 'Conditions de logement (surpeuplement)',
			),
			array(
				'id' => '8',
				'code' => '1008',
				'name' => 'Eloignement entre le lieu de résidence et le lieu de travail',
			),
			array(
				'id' => '9',
				'code' => '1009',
				'name' => 'Autres',
			),
		);
	}

?>
