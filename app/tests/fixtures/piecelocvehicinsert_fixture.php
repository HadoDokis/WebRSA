<?php

	class PiecelocvehicinsertFixture extends CakeTestFixture {
		var $name = 'Piecelocvehicinsert';
		var $table = 'pieceslocsvehicinsert';
		var $import = array( 'table' => 'pieceslocsvehicinsert', 'connection' => 'default', 'records' => false);
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
