<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AprePieceapreFixture extends CakeAppTestFixture {
		var $name = 'AprePieceapre';
		var $table = 'apres_piecesapre';
		var $import = array( 'table' => 'apres_piecesapre', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'pieceapre_id' => '1',
			),
		);
	}

?>
