<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TraitementtypepdoFixture extends CakeAppTestFixture {
		var $name = 'Traitementtypepdo';
		var $table = 'traitementstypespdos';
		var $import = array( 'table' => 'traitementstypespdos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>