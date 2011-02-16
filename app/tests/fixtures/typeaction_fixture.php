<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TypeactionFixture extends CakeAppTestFixture {
		var $name = 'Typeaction';
		var $table = 'typesactions';
		var $import = array( 'table' => 'typesactions', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'libellé',
			)
		);
	}

?>
