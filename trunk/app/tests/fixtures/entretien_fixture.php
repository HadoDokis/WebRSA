<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class EntretienFixture extends CakeAppTestFixture {
		var $name = 'Entretien';
		var $table = 'entretiens';
		var $import = array( 'table' => 'entretiens', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>