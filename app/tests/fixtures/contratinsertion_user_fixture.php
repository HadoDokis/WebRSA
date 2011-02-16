<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ContratinsertionUserFixture extends CakeAppTestFixture {
		var $name = 'ContratinsertionUser';
		var $table = 'contratsinsertion_users';
		var $import = array( 'table' => 'contratsinsertion_users', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'user_id' => '1',
				'contratinsertion_id' => '1',
				'id' => '1',
			),
		);
	}

?>
