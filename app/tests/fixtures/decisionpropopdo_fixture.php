<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DecisionpropopdoFixture extends CakeAppTestFixture {
		var $name = 'Decisionpropopdo';
		var $table = 'decisionspropospdos';
		var $import = array( 'table' => 'decisionspropospdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'datedecisionpdo' => null,
				'decisionpdo_id' => '1',
				'commentairepdo' => null,
				'isvalidation' => null,
				'validationdecision' => null,
				'datevalidationdecision' => null,
				'etatdossierpdo' => 'instrencours',
				'propopdo_id' => '1',
				'commentairedecision' => null,
				'avistechnique' => null,
				'dateavistechnique' => null,
				'commentaireavistechnique' => null,
				'created' => null,
				'modified' => null,
			),
			array(
				'id' => '2',
				'datedecisionpdo' => null,
				'decisionpdo_id' => '1',
				'commentairepdo' => null,
				'isvalidation' => null,
				'validationdecision' => null,
				'datevalidationdecision' => null,
				'etatdossierpdo' => null,
				'propopdo_id' => '2',
				'commentairedecision' => null,
				'avistechnique' => null,
				'dateavistechnique' => null,
				'commentaireavistechnique' => null,
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
