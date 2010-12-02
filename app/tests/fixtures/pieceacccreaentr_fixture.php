<?php

	class PieceacccreaentrFixture extends CakeTestFixture {
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
