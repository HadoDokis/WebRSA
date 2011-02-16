<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AvispcgdroitrsaFixture extends CakeAppTestFixture {
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
