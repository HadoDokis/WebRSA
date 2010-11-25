<?php

	class DetailressourcemensuelleFixture extends CakeTestFixture {
		var $name = 'Detailressourcemensuelle';
		var $table = 'detailsressourcesmensuelles';
		var $import = array( 'table' => 'detailsressourcesmensuelles', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'ressourcemensuelle_id' => 1,
				'natress' => null,
				'mtnatressmen' => null,
				'abaneu' => null,
				'dfpercress' => null,
				'topprevsubsress' => null,
			)
		);
	}

?>
