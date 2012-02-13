<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PermisbFixture extends CakeAppTestFixture {
		var $name = 'Permisb';
		var $table = 'permisb';
		var $import = array( 'table' => 'permisb', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'code' => null,
				'conduite' => null,
				'dureeform' => null,
				'montantaide' => null,
				'tiersprestataireapre_id' => '1',
			)
		);
	}

?>
