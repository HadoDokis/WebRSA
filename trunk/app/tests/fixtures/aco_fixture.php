<?php

	class AcoFixture extends CakeTestFixture {
		var $name = 'Aco';
		var $table = 'acos';
		var $import = array( 'table' => 'acos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'parent_id' => 0,
				'model' => null,
				'foreign_key' => 0,
				'alias' => 'Dossiers',
				'lft' => 1,
				'rght' => 6,
				),
			array(
				'id' => 2,
				'parent_id' => 1,
				'model' => null,
				'foreign_key' => 0,
				'alias' => 'Dossiers:index',
				'lft' => 4,
				'rght' => 5,
			),
			array(
				'id' => 3,
				'parent_id' => 0,
				'model' => null,
				'foreign_key' => 0,
				'alias' => 'Users',
				'lft' => 2,
				'rght' => 3,
			),
		);
	}

?>
