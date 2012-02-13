<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TitresejourFixture extends CakeAppTestFixture {
		var $name = 'Titresejour';
		var $table = 'titressejour';
		var $import = array( 'table' => 'titressejour', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>