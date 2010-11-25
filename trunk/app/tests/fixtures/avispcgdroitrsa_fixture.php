<?php

	class AvispcgdroitrsaFixture extends CakeTestFixture {
		var $name = 'Avispcgdroitrsa';
		var $table = 'avispcgdroitsrsa';
		var $import = array( 'table' => 'avispcgdroitsrsa', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'dossier_id' => 1,
				'avisdestpairsa' => null,
				'dtavisdestpairsa' => null,
				'nomtie' => null,
				'typeperstie' => null,
			)
		);
	}

?>
