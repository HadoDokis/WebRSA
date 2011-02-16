<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PrestformFixture extends CakeAppTestFixture {
		var $name = 'Prestform';
		var $table = 'prestsform';
		var $import = array( 'table' => 'prestsform', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'actioninsertion_id' => '1',
				'refpresta_id' => '1',
				'lib_presta' => null,
				'date_presta' => null,
			)
		);
	}

?>
