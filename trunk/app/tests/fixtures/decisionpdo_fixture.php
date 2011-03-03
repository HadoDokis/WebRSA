<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DecisionpdoFixture extends CakeAppTestFixture {
		var $name = 'Decisionpdo';
		var $table = 'decisionspdos';
		var $import = array( 'table' => 'decisionspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => 'decisionpdolibelle1',
			),
			array(
				'id' => '2',
				'libelle' => 'decisionpdolibelle2',
			),
		);
	}

?>
