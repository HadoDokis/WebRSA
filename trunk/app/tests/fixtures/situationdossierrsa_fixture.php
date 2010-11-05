<?php

	class SituationdossierrsaFixture extends CakeTestFixture {
		var $name = 'Situationdossierrsa';
		var $table = 'situationsdossiersrsa';
		var $import = array( 'table' => 'situationsdossiersrsa', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossier_id' => '1',
				'etatdosrsa' => '1',
				'dtrefursa' => null,
				'moticlorsa' => null,
				'dtclorsa' => null,
				'motirefursa' => null,
			),
			array(
				'id' => '2',
				'dossier_id' => '2',
				'etatdosrsa' => '3',
				'dtrefursa' => null,
				'moticlorsa' => null,
				'dtclorsa' => null,
				'motirefursa' => null,
			),
			array(
				'id' => '3',
				'dossier_id' => '3',
				'etatdosrsa' => '2',
				'dtrefursa' => null,
				'moticlorsa' => null,
				'dtclorsa' => null,
				'motirefursa' => null,
			),
			array(
				'id' => '4',
				'dossier_id' => '4',
				'etatdosrsa' => '4',
				'dtrefursa' => null,
				'moticlorsa' => null,
				'dtclorsa' => null,
				'motirefursa' => null,
			),
			array(
				'id' => '5',
				'dossier_id' => '5',
				'etatdosrsa' => '4',
				'dtrefursa' => null,
				'moticlorsa' => null,
				'dtclorsa' => null,
				'motirefursa' => null,
			),
		);
	}

?>
