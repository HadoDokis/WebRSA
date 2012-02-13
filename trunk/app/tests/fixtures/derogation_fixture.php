<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DerogationFixture extends CakeAppTestFixture {
		var $name = 'Derogation';
		var $table = 'derogations';
		var $import = array( 'table' => 'derogations', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'avispcgpersonne_id' => '1',
				'typedero' => null,
				'avisdero' => null,
				'ddavisdero' => null,
				'dfavisdero' => null,
			),
		);
	}

?>
