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
				'numdepins' => '030',
				'typeserins' => 'F',
				'numcomins' => '189',
				'numagrins' => '11',
				'type_voie' => 'BCH',
			),
			array(
				'id' => '2',
				'lib_service' => null,
				'num_rue' => '16',
				'nom_rue' => 'collines',
				'complement_adr' => null,
				'code_insee' => '30900',
				'code_postal' => '30000',
				'ville' => 'Nimes',
				'numdepins' => '030',
				'typeserins' => 'F',
				'numcomins' => '189',
				'numagrins' => null,
				'type_voie' => 'BCH',
			),
		);
	}
		
?>