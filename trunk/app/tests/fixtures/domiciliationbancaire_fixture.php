<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DomiciliationbancaireFixture extends CakeAppTestFixture {
		var $name = 'Domiciliationbancaire';
		var $table = 'domiciliationsbancaires';
		var $import = array( 'table' => 'domiciliationsbancaires', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>