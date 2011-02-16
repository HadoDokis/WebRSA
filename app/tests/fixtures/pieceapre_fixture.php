<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PieceapreFixture extends CakeAppTestFixture {
		var $name = 'Pieceapre';
		var $table = 'piecesapre';
		var $import = array( 'table' => 'piecesapre', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'Attestation CAF datant du dernier mois de prestation versée',
			),
		);
	}

?>
