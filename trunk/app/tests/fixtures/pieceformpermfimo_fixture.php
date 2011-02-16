<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PieceformpermfimoFixture extends CakeAppTestFixture {
		var $name = 'Pieceformpermfimo';
		var $table = 'piecesformspermsfimo';
		var $import = array( 'table' => 'piecesformspermsfimo', 'connection' => 'default', 'records' => false);
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
