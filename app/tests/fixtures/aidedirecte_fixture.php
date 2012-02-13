<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AidedirecteFixture extends CakeAppTestFixture {
		var $name = 'Aidedirecte';
		var $table = 'aidesdirectes';
		var $import = array( 'table' => 'aidesdirectes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'actioninsertion_id' => '1',
				'lib_aide' => null,
				'typo_aide' => null,
				'date_aide' => null,
			),
		);
	}

?>
