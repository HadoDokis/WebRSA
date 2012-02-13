<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DetailfreinformRevFixture extends CakeAppTestFixture {
		var $name = 'DetailfreinformRev';
		var $table = 'detailsfreinforms_revs';
		var $import = array( 'table' => 'detailsfreinforms_revs', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>