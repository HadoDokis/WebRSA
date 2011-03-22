<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class IdentificationfluxFixture extends CakeAppTestFixture {
		var $name = 'Identificationflux';
		var $table = 'identificationsflux';
		var $import = array( 'table' => 'identificationsflux', 'connection' => 'default', 'records' => false );
		var $records = array(
			array(
				'id' => '1',
				'applieme' => null,
				'numversionapplieme' => null,
				'typeflux' => null,
				'natflux' => null,
				'dtcreaflux' => null,
				'heucreaflux' => null,
				'dtref' => null,
			),
		);
	}

?>
