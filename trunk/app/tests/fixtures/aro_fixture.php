<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AroFixture extends CakeAppTestFixture {
		var $name = 'Aro';
		var $table = 'aros';
		var $import = array( 'table' => 'aros', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'parent_id' => 0,
				'model' => 'Group',
				'foreign_key' => 1,
				'alias' => 'Administrateurs',
				'lft' => 1,
				'rght' => 4,
			),
			array(
				'id' => 2,
				'parent_id' => 1,
				'model' => 'Utilisateur',
				'foreign_key' => 2,
				'alias' => 'test',
				'lft' => 2,
				'rght' => 3,
			),
		);
	}

?>
