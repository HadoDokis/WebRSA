<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class OrientstructServiceinstructeurFixture extends CakeAppTestFixture {
		var $name = 'OrientstructServiceinstructeur';
		var $table = 'orientsstructs_servicesinstructeurs';
		var $import = array( 'table' => 'orientsstructs_servicesinstructeurs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'orientstruct_id' => '1',
				'serviceinstructeur_id' => '1',
				'id' => '1',
			),
		);
	}

?>
