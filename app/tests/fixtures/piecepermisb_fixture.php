<?php

	class PiecepermisbFixture extends CakeTestFixture {
		var $name = 'Piecepermisb';
		var $table = 'piecespermisb';
		var $import = array( 'table' => 'piecespermisb', 'connection' => 'default', 'records' => false);
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
