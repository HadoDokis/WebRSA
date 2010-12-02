<?php

	class ActprofPieceactprofFixture extends CakeTestFixture {
		var $name = 'ActprofPieceactprof';
		var $table = 'actsprofs_piecesactsprofs';
		var $import = array( 'table' => 'actsprofs_piecesactsprofs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'actprof_id' => '1',
				'pieceactprof_id' => '1',
			)
		);
	}

?>
