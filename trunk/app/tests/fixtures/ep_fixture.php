<?php

	class EpFixture extends CakeTestFixture {
		var $name = 'Ep';
		var $table = 'eps';
		var $import = array( 'table' => 'eps', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'name',
				'regroupementep_id' => '1',
				'saisineepreorientsr93' => 'nontraite',
				'saisineepbilanparcours66' => 'nontraite',
				'saisineepdpdo66' => 'nontraite',
			),
		);
	}

?>
