<?php

	class ApreEtatliquidatifFixture extends CakeTestFixture {
		var $name = 'ApreEtatliquidatif';
		var $table = 'apres_etatsliquidatifs';
		var $import = array( 'table' => 'apres_etatsliquidatifs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'etatliquidatif_id' => '1',
				'montantattribue' => null,
			),
			array(
				'id' => '2',
				'apre_id' => '2',
				'etatliquidatif_id' => '2',
				'montantattribue' => null,
			),
			array(
				'id' => '3',
				'apre_id' => '3',
				'etatliquidatif_id' => '3',
				'montantattribue' => null,
			),
		);
	}

?>
