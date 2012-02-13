<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailfreinformFixture extends CakeAppTestFixture {
		var $name = 'Detailfreinform';
		var $table = 'detailsfreinforms';
		var $import = array( 'table' => 'detailsfreinforms', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>