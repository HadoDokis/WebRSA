<?php

	class PieceactprofFixture extends CakeTestFixture {
		var $name = 'Pieceactprof';
		var $table = 'piecesactsprofs';
		var $import = array( 'table' => 'piecesactsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'libellé1',
			),
			array(
				'id' => '2',
				'libelle' => 'libellé2',
			),
		);
	}

?>
