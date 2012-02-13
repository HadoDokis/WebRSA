<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PiecelocvehicinsertFixture extends CakeAppTestFixture {
		var $name = 'Piecelocvehicinsert';
		var $table = 'pieceslocsvehicinsert';
		var $import = array( 'table' => 'pieceslocsvehicinsert', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'piecelocvehicinsertlibelle1',
			),
			array(
				'id' => '2',
				'libelle' => 'piecelocvehicinsertlibelle2',
			),
		);
	}

?>
