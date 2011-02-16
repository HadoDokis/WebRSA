<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class RattachementFixture extends CakeAppTestFixture {
		var $name = 'Rattachement';
		var $table = 'rattachements';
		var $import = array( 'table' => 'rattachements', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>