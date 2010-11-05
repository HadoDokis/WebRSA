<?php

	class AprePieceapreFixture extends CakeTestFixture {
		var $name = 'AprePieceapre';
		var $table = 'apres_piecesapre';
		var $import = array( 'table' => 'apres_piecesapre', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'pieceapre_id' => '1',
			),
		);
	}

?>
