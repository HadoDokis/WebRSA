<?php

	class PiecepdoFixture extends CakeTestFixture {
		var $name = 'Piecepdo';
		var $table = 'piecespdos';
		var $import = array( 'table' => 'piecespdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'libelle' => null,
				'dateajout' => null,
			)
		);
	}

?>
