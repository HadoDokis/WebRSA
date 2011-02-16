<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PieceacccreaentrFixture extends CakeAppTestFixture {
		var $name = 'Pieceacccreaentr';
		var $table = 'piecesaccscreaentr';
		var $import = array( 'table' => 'piecesaccscreaentr', 'connection' => 'default', 'records' => false);
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
