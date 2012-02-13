<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Nonorientationproep93Fixture extends CakeAppTestFixture {
		var $name = 'Nonorientationproep93';
		var $table = 'nonorientationsproseps93';
		var $import = array( 'table' => 'nonorientationsproseps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossierep_id' => '1',
				'orientstruct_id' => '1001',
				'created' => null,
				'modified' => null,
			),
			array(
				'id' => '2',
				'dossierep_id' => '2',
				'orientstruct_id' => '2002',
				'created' => null,
				'modified' => null,
			),
		);//Personnes du 93//
	}

?>
