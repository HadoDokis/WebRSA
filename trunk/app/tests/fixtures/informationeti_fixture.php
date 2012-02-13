<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class InformationetiFixture extends CakeAppTestFixture {
		var $name = 'Informationeti';
		var $table = 'informationseti';
		var $import = array( 'table' => 'informationseti', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>