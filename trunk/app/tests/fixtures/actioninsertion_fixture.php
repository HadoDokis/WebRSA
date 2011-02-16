<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ActioninsertionFixture extends CakeAppTestFixture {
		var $name = 'Actioninsertion';
		var $table = 'actionsinsertion';
		var $import = array( 'table' => 'actionsinsertion', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'contratinsertion_id' => '1',
				'dd_action' => null,
				'df_action' => null,
				'lib_action' => null,
				'commentaire_action' => null,
			),
		);
	}

?>
