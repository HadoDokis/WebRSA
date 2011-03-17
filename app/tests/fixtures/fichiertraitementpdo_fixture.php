<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class FichiertraitementpdoFixture extends CakeAppTestFixture {
		var $name = 'Fichiertraitementpdo';
		var $table = 'fichierstraitementspdos';
		var $import = array( 'table' => 'fichierstraitementspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>