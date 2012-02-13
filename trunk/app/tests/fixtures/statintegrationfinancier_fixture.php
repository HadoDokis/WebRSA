<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class StatintegrationfinancierFixture extends CakeAppTestFixture {
		var $name = 'Statintegrationfinancier';
		var $table = 'statintegrationfinancier';
		var $import = array( 'table' => 'statintegrationfinancier', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'nom_fichier' => 'NRSACGIM_RSAFIM_20110205_B0512254.RCV',
				'anomalies' => '0',
				'dossiers_rsa' => '160352',
				'identificationsflux' => '116',
				'infosfinancieres' => '1475658',
				'totalisationsacomptes' => '120',
				'transmissionsflux' => '37',
			),
		);
	}

?>