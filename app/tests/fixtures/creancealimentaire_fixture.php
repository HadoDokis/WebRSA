<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class CreancealimentaireFixture extends CakeAppTestFixture {
		var $name = 'Creancealimentaire';
		var $table = 'creancesalimentaires';
		var $import = array( 'table' => 'creancesalimentaires', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'etatcrealim' => null,
				'ddcrealim' => null,
				'dfcrealim' => null,
				'orioblalim' => null,
				'motidiscrealim' =>  null,
				'commcrealim' => null,
				'mtsancrealim' => null,
				'topdemdisproccrealim' => null,
				'engproccrealim' => null,
				'verspa' => null,
				'topjugpa' => null,
				'personne_id' => '1001',
			),
		);
	}

?>
