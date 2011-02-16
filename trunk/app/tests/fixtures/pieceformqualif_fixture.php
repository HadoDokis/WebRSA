<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PieceformqualifFixture extends CakeAppTestFixture {
		var $name = 'Pieceformqualif';
		var $table = 'piecesformsqualifs';
		var $import = array( 'table' => 'piecesformsqualifs', 'connection' => 'default', 'records' => false);
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
