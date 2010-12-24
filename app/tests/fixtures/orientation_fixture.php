<?php

	class OrientationFixture extends CakeTestFixture {
		var $name = 'Orientation';
		var $table = 'orientations';
		var $import = array( 'table' => 'orientations', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'raisocorgorie' => null,
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'codepos' => null,
				'locaadr' => null,
				'numtelorgorie' => null,
				'dtrvorgorie' => null,
				'hrrvorgorie' => null,
				'libadrrvorgorie' => null,
				'numtelrvorgorie' => null,
			),
			array(
				'id' => '2',
				'personne_id' => '2',
				'raisocorgorie' => null,
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'codepos' => null,
				'locaadr' => null,
				'numtelorgorie' => null,
				'dtrvorgorie' => null,
				'hrrvorgorie' => null,
				'libadrrvorgorie' => null,
				'numtelrvorgorie' => null,
			),
		);
	}

?>
