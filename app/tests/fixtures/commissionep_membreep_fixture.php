<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class CommissionepMembreepFixture extends CakeAppTestFixture {
		var $name = 'CommissionepMembreep';
		var $table = 'commissionseps_membreseps';
		var $import = array( 'table' => 'commissionseps_membreseps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'commissionep_id' => '3',
				'membreep_id' => '1',
				'suppleant' => '0',
				'suppleant_id' => null,
				'reponse' => 'confirme',
				'presence' => null,
			),
			array(
				'id' => '2',
				'commissionep_id' => '6',
				'membreep_id' => '2',
				'suppleant' => '0',
				'suppleant_id' => null,
				'reponse' => 'confirme',
				'presence' => null,
			),
		);
	}

?>
