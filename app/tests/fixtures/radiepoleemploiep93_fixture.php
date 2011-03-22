<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Radiepoleemploiep93Fixture extends CakeAppTestFixture {
		var $name = 'Radiespoleemploieps93';
		var $table = 'radiespoleemploieps93';
		var $import = array( 'table' => 'radiespoleemploieps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossierep_id' => '1',
				'historiqueetatpe_id' => '1',
				'commentaire' => null,
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
