<?php

	class PieceformpermfimoFixture extends CakeTestFixture {
		var $name = 'Pieceformpermfimo';
		var $table = 'piecesformspermsfimo';
		var $import = array( 'table' => 'piecesformspermsfimo', 'connection' => 'default', 'records' => false);
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
