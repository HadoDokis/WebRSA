<?php

	class AroFixture extends CakeTestFixture {
		var $name = 'Aro';
		var $table = 'aros';
		var $import = array( 'table' => 'aros', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'parent_id' => 0,
				'model' => null,
				'foreign_key' => 1,
				'alias' => 'Group:Administrateurs',
				'lft' => 1,
				'rght' => 4,
			),
			array(
				'id' => 2,
				'parent_id' => 0,
				'model' => null,
				'foreign_key' => 2,
				'alias' => 'Utilisateur:test',
				'lft' => 2,
				'rght' => 3,
			),
		);
	}

?>