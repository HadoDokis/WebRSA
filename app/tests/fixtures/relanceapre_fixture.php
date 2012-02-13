<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class RelanceapreFixture extends CakeAppTestFixture {
		var $name = 'Relanceapre';
		var $table = 'relancesapres';
		var $import = array( 'table' => 'relancesapres', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'daterelance' => null,
				'etatdossierapre' => null,
				'commentairerelance' => null,
			)
		);
	}

?>
