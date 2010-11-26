<?php

	class ParcoursFixture extends CakeTestFixture {
		var $name = 'Parcours';
		var $table = 'parcours';
		var $import = array( 'table' => 'parcours', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'natparcocal' => null,
				'natparcomod' => null,
				'toprefuparco' => null,
				'motimodparco' => null,
				'raisocorgdeciorie' => null,
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'codepos' => null,
				'locaadr' => null,
				'numtelorgdeciorie' => null,
				'dtrvorgdeciorie' => null,
				'hrrvorgdeciorie' => null,
				'libadrrvorgdeciorie' => null,
				'numtelrvorgdeciorie' => null,
			)
		);
	}

?>
