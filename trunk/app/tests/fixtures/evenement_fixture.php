<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class EvenementFixture extends CakeAppTestFixture {
		var $name = 'Evenement';
		var $table = 'evenements';
		var $import = array( 'table' => 'evenements', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>