<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PropopdoStatutpdoFixture extends CakeAppTestFixture {
		var $name = 'PropopdoStatutpdo';
		var $table = 'propospdos_statutspdos';
		var $import = array( 'table' => 'propospdos_statutspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'statutpdo_id' => '1',
			)
		);
	}

?>
