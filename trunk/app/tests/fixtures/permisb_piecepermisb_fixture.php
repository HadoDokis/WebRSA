<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PermisbPiecepermisbFixture extends CakeAppTestFixture {
		var $name = 'PermisbPiecepermisb';
		var $table = 'permisb_piecespermisb';
		var $import = array( 'table' => 'permisb_piecespermisb', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'permisb_id' => '1',
				'piecepermisb_id' => '1',
			)
		);
	}

?>
