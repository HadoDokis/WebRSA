<?php

	class PropopdoFixture extends CakeTestFixture {
		var $name = 'Propopdo';
		var $table = 'propospdos';
		var $import = array( 'table' => 'propospdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossier_rsa_id' => '1',
				'datedecisionpdo' => null,
				'commentairepdo' => null,
				'motifpdo' => null,
				'typepdo_id' => '3',
				'decisionpdo_id' => null,
				'typenotifpdo_id' => null,
				'datereceptionpdo' => null,
				'statutdecision' => null,
				'originepdo_id' => null,
			),
			array(
				'id' => '2',
				'dossier_rsa_id' => '2',
				'datedecisionpdo' => null,
				'commentairepdo' => null,
				'motifpdo' => null,
				'typepdo_id' => '3',
				'decisionpdo_id' => null,
				'typenotifpdo_id' => null,
				'datereceptionpdo' => null,
				'statutdecision' => null,
				'originepdo_id' => null,
			),
		);
	}

?>
