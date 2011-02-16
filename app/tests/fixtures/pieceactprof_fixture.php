<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PieceactprofFixture extends CakeAppTestFixture {
		var $name = 'Pieceactprof';
		var $table = 'piecesactsprofs';
		var $import = array( 'table' => 'piecesactsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'libellé',
			),
			array(
				'id' => '2',
				'libelle' => 'libellé2',
			),
		);
	}

?>
