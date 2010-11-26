<?php

	class PaiementfoyerFixture extends CakeTestFixture {
		var $name = 'Paiementfoyer';
		var $table = 'paiementsfoyers';
		var $import = array( 'table' => 'paiementsfoyers', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'foyer_id' => '1',
				'topverstie' => null,
				'modepai' => null,
				'topribconj' => null,
				'titurib' => null,
				'nomprenomtiturib' => null,
				'etaban' => null,
				'guiban' => null,
				'numcomptban' => null,
				'clerib' => null,
				'comban' => null,
			)
		);
	}

?>
