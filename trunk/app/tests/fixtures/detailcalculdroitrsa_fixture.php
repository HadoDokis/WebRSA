<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailcalculdroitrsaFixture extends CakeAppTestFixture {
		var $name = 'Detailcalculdroitrsa';
		var $table = 'detailscalculsdroitsrsa';
		var $import = array( 'table' => 'detailscalculsdroitsrsa', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'detaildroitrsa_id' => '1',
				'natpf' => null,
				'sousnatpf' => null,
				'ddnatdro' => null,
				'dfnatdro' => null,
				'mtrsavers' => null,
				'dtderrsavers' => null, 
			)
		);
	}

?>
