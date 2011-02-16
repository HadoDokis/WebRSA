<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class RegroupementzonegeoFixture extends CakeAppTestFixture {
		var $name = 'Regroupementzonegeo';
		var $table = 'regroupementszonesgeo';
		var $import = array( 'table' => 'regroupementszonesgeo', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>