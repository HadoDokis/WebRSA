<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class HistoriqueetatpeFixture extends CakeAppTestFixture {
		var $name = 'Historiqueetatpe';
		var $table = 'historiqueetatspe';
		var $import = array( 'table' => 'historiqueetatspe', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '2',
				'informationpe_id' => '1',
				'identifiantpe' => '06110442000',
				'date' => '2010-02-08',
				'etat' => 'inscription',
				'code' => '1',
				'motif' => null,
			),
			array(
				'id' => '3',
				'informationpe_id' => '2',
				'identifiantpe' => '06117179000',
				'date' => '2008-01-15',
				'etat' => 'inscription',
				'code' => '1',
				'motif' => null,
			),
			array(
				'id' => '4',
				'informationpe_id' => '3',
				'identifiantpe' => '06130610000',
				'date' => '2009-12-28',
				'etat' => 'inscription',
				'code' => '1',
				'motif' => null,
			),
		);
	}
?>
