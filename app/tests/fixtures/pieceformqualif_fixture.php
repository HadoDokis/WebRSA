<?php

	class PieceformqualifFixture extends CakeTestFixture {
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
