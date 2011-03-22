<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class FichiertraitementpdoFixture extends CakeAppTestFixture {
		var $name = 'Fichiertraitementpdo';
		var $table = 'fichierstraitementspdos';
		var $import = array( 'table' => 'fichierstraitementspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'name1?',
				'traitementpdo_id' => '1',
				'type' => 'courrier',
				'document' => null,
				'cmspath' => null,
				'mime' => 'mime?',
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
