<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class OriginepdoFixture extends CakeAppTestFixture {
		var $name = 'Originepdo';
		var $table = 'originespdos';
		var $import = array( 'table' => 'originespdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'libellé',
			),
		);
	}

?>
