<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AcccreaentrFixture extends CakeAppTestFixture {
		var $name = 'Acccreaentr';
		var $table = 'accscreaentr';
		var $import = array( 'table' => 'accscreaentr', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'nacre' => null,
				'microcredit' => null,
				'projet' => null,
				'montantaide' => null,
			),
		);
	}

?>
