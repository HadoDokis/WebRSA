<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PropopdoSituationpdoFixture extends CakeAppTestFixture {
		var $name = 'PropopdoSituationpdo';
		var $table = 'propospdos_situationspdos';
		var $import = array( 'table' => 'propospdos_situationspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'situationpdo_id' => '1',
			)
		);
	}

?>
