<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class EpFixture extends CakeAppTestFixture {
		var $name = 'Ep';
		var $table = 'eps';
		var $import = array( 'table' => 'eps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'CLI 1 Equipe 1.1',
				'identifiant' => 'EP1.1',
				'regroupementep_id' => '1',
				'defautinsertionep66' => 'nontraite',
				'saisineepbilanparcours66' => 'nontraite',
				'saisineepdpdo66' => 'nontraite',
				'nonrespectsanctionep93' => 'cg',
				'saisineepreorientsr93' => 'nontraite',//'cg',
				'nonorientationpro58' => 'nontraite',
				'regressionorientationep58' => 'nontraite',
				'radiepoleemploiep93' => 'nontraite',
			),
			array(
				'id' => '2',
				'name' => 'CLI 1 Equipe 1.2',
				'identifiant' => 'EP1.2',
				'regroupementep_id' => '1',
				'defautinsertionep66' => 'nontraite',
				'saisineepbilanparcours66' => 'nontraite',
				'saisineepdpdo66' => 'nontraite',
				'nonrespectsanctionep93' => 'cg',
				'saisineepreorientsr93' => 'nontraite',//'cg',
				'nonorientationpro58' => 'nontraite',
				'regressionorientationep58' => 'nontraite',
				'radiepoleemploiep93' => 'nontraite',
			),
			array(
				'id' => '3',
				'name' => 'CLI 2 Equipe 2.1',
				'identifiant' => 'EP2.1',
				'regroupementep_id' => '2',
				'defautinsertionep66' => 'nontraite',
				'saisineepbilanparcours66' => 'nontraite',
				'saisineepdpdo66' => 'nontraite',
				'nonrespectsanctionep93' => 'cg',
				'saisineepreorientsr93' => 'nontraite',//'cg',
				'nonorientationpro58' => 'nontraite',
				'regressionorientationep58' => 'nontraite',
				'radiepoleemploiep93' => 'nontraite',
			),
		);
	}

?>
