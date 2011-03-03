<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PieceacqmatprofFixture extends CakeAppTestFixture {
		var $name = 'Pieceacqmatprof';
		var $table = 'piecesacqsmatsprofs';
		var $import = array( 'table' => 'piecesacqsmatsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'pieceacqmatproflibelle1',
			),
			array(
				'id' => '2',
				'libelle' => 'pieceacqmatproflibelle2',
			),
		);
	}

?>
