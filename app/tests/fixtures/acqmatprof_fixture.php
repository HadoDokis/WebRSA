<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AcqmatprofFixture extends CakeAppTestFixture {
		var $name = 'Acqmatprof';
		var $table = 'acqsmatsprofs';
		var $import = array( 'table' => 'acqsmatsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'besoins' => null,
				'montantaide' => null,
			)
		);
	}

?>
