<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TempcessationFixture extends CakeAppTestFixture {
		var $name = 'Tempcessation';
		var $table = 'tempcessations';
		var $import = array( 'table' => 'tempcessations', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'nir' => null,
				'identifiantpe' => null,
				'nom' => null,
				'prenom' => null,
				'dtnai' => null,
				'datecessation' => null,
				'motifcessation' => null,
			)
		);
	}

?>
