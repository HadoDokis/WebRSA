<?php

	class ServiceinstructeurFixture extends CakeTestFixture {
		var $name = 'Serviceinstructeur';
		var $table = 'servicesinstructeurs';
		var $import = array( 'table' => 'servicesinstructeurs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'lib_service' => 'Service 1',
				'num_rue' => '16',
				'nom_rue' => 'collines',
				'complement_adr' => null,
				'code_insee' => '30900',
				'code_postal' => '30000',
				'ville' => 'Nimes',
				'numdepins' => '034',
				'typeserins' => 'P',
				'numcomins' => '111',
				'numagrins' => '11',
				'type_voie' => 'ARC',
			),
			array(
				'id' => '2',
				'lib_service' => 'Service 2',
				'num_rue' => '775',
				'nom_rue' => 'moulin',
				'complement_adr' => null,
				'code_insee' => '34080',
				'code_postal' => '34000',
				'ville' => 'Lattes',
				'numdepins' => '034',
				'typeserins' => 'P',
				'numcomins' => '111',
				'numagrins' => null,
				'type_voie' => 'ARC',
			),
		);
	}

?>
