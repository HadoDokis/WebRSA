<?php

	class OriginepdoFixture extends CakeTestFixture {
		var $name = 'Originepdo';
		var $table = 'originespdos';
		var $import = array( 'table' => 'originespdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'libellé',
			),
		);
	}

?>
