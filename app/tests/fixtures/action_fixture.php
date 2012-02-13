<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ActionFixture extends CakeAppTestFixture {
		var $name = 'Action';
		var $table = 'actions';
		var $import = array( 'table' => 'actions', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'typeaction_id' => '1',
				'code' => '04',
				'libelle' => 'Aide pour la garde des enfants',
			),
			array(
				'id' => '2',
				'typeaction_id' => '2',
				'code' => '21',
				'libelle' => 'Démarche liée à la santé',
			),
			array(
				'id' => '3',
				'typeaction_id' => '3',
				'code' => '31',
				'libelle' => 'Recherche d\'un logement',
			),
			array(
				'id' => '4',
				'typeaction_id' => '4',
				'code' => '44',
				'libelle' => 'Stage de conduite automobile (véhicules légers)',
			),
			array(
				'id' => '5',
				'typeaction_id' => '5',
				'code' => '51',
				'libelle' => 'Aide ou suivi pour une recherche d\'emploi',
			),
		);
	}

?>
