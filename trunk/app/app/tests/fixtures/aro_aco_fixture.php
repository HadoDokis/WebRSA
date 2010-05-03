<?php

	class AroAcoFixture extends CakeTestFixture {
		var $name = 'ArosAco';
		var $table = 'aros_acos';
		var $import = array( 'table' => 'aros_acos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'aro_id' => 1,
				'aco_id' => 1,
				'_create' => 1,
				'_read' => 1,
				'_update' => 1,
				'_delete' => 1,
			),
			array(
				'id' => 2,
				'aro_id' => 1,
				'aco_id' => 2,
				'_create' => 1,
				'_read' => 1,
				'_update' => 1,
				'_delete' => 1,
			),
			array(
				'id' => 3,
				'aro_id' => 1,
				'aco_id' => 3,
				'_create' => 1,
				'_read' => 1,
				'_update' => 1,
				'_delete' => 1,
			),
			array(
				'id' => 4,
				'aro_id' => 2,
				'aco_id' => 1,
				'_create' => 1,
				'_read' => 1,
				'_update' => 1,
				'_delete' => 1,
			),
			array(
				'id' => 5,
				'aro_id' => 2,
				'aco_id' => 2,
				'_create' => -1,
				'_read' => -1,
				'_update' => -1,
				'_delete' => -1,
			),
			array(
				'id' => 6,
				'aro_id' => 2,
				'aco_id' => 3,
				'_create' => 1,
				'_read' => 1,
				'_update' => 1,
				'_delete' => 1,
			),
		);
	}

?>