<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TotalisationacompteFixture extends CakeAppTestFixture {
		var $name = 'Totalisationacompte';
		var $table = 'totalisationsacomptes';
		var $import = array( 'table' => 'totalisationsacomptes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'identificationflux_id' => '1',
				'type_totalisation' => null,
				'mttotsoclrsa' => null,
				'mttotsoclmajorsa' => null,
				'mttotlocalrsa' => null,
				'mttotrsa' => null,
			),
		);
	}

?>
