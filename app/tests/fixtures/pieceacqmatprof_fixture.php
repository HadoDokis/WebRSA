<?php

	class PieceacqmatprofFixture extends CakeTestFixture {
		var $name = 'Pieceacqmatprof';
		var $table = 'piecesacqsmatsprofs';
		var $import = array( 'table' => 'piecesacqsmatsprofs', 'connection' => 'default', 'records' => false);
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
