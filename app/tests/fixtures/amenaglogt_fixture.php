<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AmenaglogtFixture extends CakeAppTestFixture {
		var $name = 'Amenaglogt';
		var $table = 'amenagslogts';
		var $import = array( 'table' => 'amenagslogts', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'typeaidelogement' => null,
				'besoins' => null,
				'montantaide' => null,
			),
		);
	}

?>
