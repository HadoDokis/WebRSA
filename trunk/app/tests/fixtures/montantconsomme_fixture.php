<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class MontantconsommeFixture extends CakeAppTestFixture {
		var $name = 'Montantconsomme';
		var $table = 'montantsconsommes';
		var $import = array( 'table' => 'montantsconsommes', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>