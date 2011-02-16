<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class FormqualifFixture extends CakeAppTestFixture {
		var $name = 'Formqualif';
		var $table = 'formsqualifs';
		var $import = array( 'table' => 'formsqualifs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>