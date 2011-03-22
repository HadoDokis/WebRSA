<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class SuspensionversementFixture extends CakeAppTestFixture {
		var $name = 'Suspensionversement';
		var $table = 'suspensionsversements';
		var $import = array( 'table' => 'suspensionsversements', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'situationdossierrsa_id' => '1',
				'motisusversrsa' => null,
				'ddsusversrsa' => null,
			),
			array(
				'id' => '2',
				'situationdossierrsa_id' => '2',
				'motisusversrsa' => null,
				'ddsusversrsa' => null,
			),
		);
	}

?>
