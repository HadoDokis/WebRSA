<?php

	class ActionFixture extends CakeTestFixture {
		var $name = 'Action';
		var $table = 'actions';
		var $import = array( 'table' => 'actions', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'typeaction_id' => '1',
				'code' => null,
				'libelle' => 'libellé',
			),
		);
	}

?>
