<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PropopdoStatutdecisionpdoFixture extends CakeAppTestFixture {
		var $name = 'PropopdoStatutdecisionpdo';
		var $table = 'propospdos_statutsdecisionspdos';
		var $import = array( 'table' => 'propospdos_statutsdecisionspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'statutdecisionpdo_id' => '1',
			)
		);
	}

?>
