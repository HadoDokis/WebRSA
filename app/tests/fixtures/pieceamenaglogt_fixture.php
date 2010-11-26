<?php

	class PieceamenaglogtFixture extends CakeTestFixture {
		var $name = 'Pieceamenaglogt';
		var $table = 'piecesamenagslogts';
		var $import = array( 'table' => 'piecesamenagslogts', 'connection' => 'default', 'records' => false);
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
