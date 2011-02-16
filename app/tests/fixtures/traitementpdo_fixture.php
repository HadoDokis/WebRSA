<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TraitementpdoFixture extends CakeAppTestFixture {
		var $name = 'Traitementpdo';
		var $table = 'traitementspdos';
		var $import = array( 'table' => 'traitementspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'descriptionpdo_id' => '1',
				'traitementtypepdo_id' => '1',
				'datereception' => null,
				'datedepart' => null,
				'hascourrier' => null,
				'hasrevenu' => null,
				'haspiecejointe' => null,
				'hasficheanalyse' => null,
			)
		);
	}

?>
