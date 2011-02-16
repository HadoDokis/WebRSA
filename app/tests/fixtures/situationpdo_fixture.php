<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class SituationpdoFixture extends CakeAppTestFixture {
		var $name = 'Situationpdo';
		var $table = 'situationspdos';
		var $import = array( 'table' => 'situationspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>