<?php

	class StructurereferenteFixture extends CakeTestFixture {
		var $name = 'Structurereferente';
		var $table = 'structuresreferentes';
		var $import = array( 'table' => 'structuresreferentes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'typeorient_id' => '1',
				'lib_struc' => 'Pole emploi Mont Sud',
				'num_voie' => '125',
				'type_voie' => 'HLE',
				'nom_voie' => 'Alco',
				'code_postal' => '34090',
				'ville' => 'Montpellier',
				'code_insee' => '34095',
				'filtre_zone_geo' => null,
				'contratengagement' => null,
				'apre' => 'O',
			),
			array(
				'id' => '2',
				'typeorient_id' => '1',
				'lib_struc' => 'Assedic Nimes',
				'num_voie' => '44',
				'type_voie' => 'ART',
				'nom_voie' => 'Parrot',
				'code_postal' => '30000',
				'ville' => 'Nimes',
				'code_insee' => '30009',
				'filtre_zone_geo' => null,
				'contratengagement' => '0',
				'apre' => null,
			),
		);
	}

?>