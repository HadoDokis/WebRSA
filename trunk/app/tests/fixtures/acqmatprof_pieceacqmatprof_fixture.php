<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AcqmatprofPieceacqmatprofFixture extends CakeAppTestFixture {
		var $name = 'AcqmatprofPieceacqmatprof';
		var $table = 'acqsmatsprofs_piecesacqsmatsprofs';
		var $import = array( 'table' => 'acqsmatsprofs_piecesacqsmatsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'acqmatprof_id' => '1',
				'pieceacqmatprof_id' => '1',
			),
		);
	}

?>
