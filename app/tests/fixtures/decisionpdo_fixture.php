<?php

	class DecisionpdoFixture extends CakeTestFixture {
		var $name = 'Decisionpdo';
		var $table = 'decisionspdos';
		var $import = array( 'table' => 'decisionspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libelle' => null,
			),
			array(
				'id' => '2',
				'libelle' => null,
			),
		);
	}

?>
