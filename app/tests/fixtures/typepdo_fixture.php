<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TypepdoFixture extends CakeAppTestFixture {
		var $name = 'Typepdo';
		var $table = 'typespdos';
		var $import = array( 'table' => 'typespdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'tyepdolibelle1'
			),
			array(
				'id' => '2',
				'libelle' => 'typepdolibelle2'
			),
		);
	}

?>
