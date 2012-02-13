<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PieceamenaglogtFixture extends CakeAppTestFixture {
		var $name = 'Pieceamenaglogt';
		var $table = 'piecesamenagslogts';
		var $import = array( 'table' => 'piecesamenagslogts', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'pieceamenaglogtlibelle1',
			),
			array(
				'id' => '2',
				'libelle' => 'pieceamenaglogtlibelle2',
			),
		);
	}

?>
