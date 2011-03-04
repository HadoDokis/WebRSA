<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ServiceinstructeurFixture extends CakeAppTestFixture {
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
			array(
				'id' => '3',
				'lib_service' => 'CCAS Saint Denis',
				'num_rue' => '2',
				'nom_rue' => 'du Caquet',
				'complement_adr' => null,
				'code_insee' => '93066',
				'code_postal' => '93200',
				'ville' => 'ST DENIS',
				'numdepins' => '093',
				'typeserins' => 'C',
				'numcomins' => '066',
				'numagrins' => '1',
				'type_voie' => 'PL',
			),
		);
	}

?>
