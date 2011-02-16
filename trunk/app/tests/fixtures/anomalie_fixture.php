<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AnomalieFixture extends CakeAppTestFixture {
		var $name = 'Anomalie';
		var $table = 'anomalies';
		var $import = array( 'table' => 'anomalies', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'foyer_id' => '1',
				'libano' => null,
			),
		);
	}

?>
