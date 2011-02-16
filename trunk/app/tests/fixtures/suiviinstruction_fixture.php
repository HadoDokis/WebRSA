<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class SuiviinstructionFixture extends CakeAppTestFixture {
		var $name = 'Suiviinstruction';
		var $table = 'suivisinstruction';
		var $import = array( 'table' => 'suivisinstruction', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossier_id' => '1',
				'etatirsa' => null,
				'date_etat_instruction' => null,
				'nomins' => null,
				'prenomins' => null,
				'numdepins' => null,
				'typeserins' => null,
				'numcomins' => null,
				'numagrins' => null,
				'suiirsa' => null,
			),
		);
	}

?>
