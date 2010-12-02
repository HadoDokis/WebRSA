<?php

	class AmenaglogtPieceamenaglogtFixture extends CakeTestFixture {
		var $name = 'AmenaglogtPieceamenaglogt';
		var $table = 'amenagslogts_piecesamenagslogts';
		var $import = array( 'table' => 'amenagslogts_piecesamenagslogts', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'amenaglogt_id' => '1',
				'pieceamenaglogt_id' => '1',
			),
		);
	}

?>
