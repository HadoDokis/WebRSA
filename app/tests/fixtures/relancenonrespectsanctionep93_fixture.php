<?php

	class Relancenonrespectsanctionep93Fixture extends CakeTestFixture {
		var $name = 'Relancenonrespectsanctionep93';
		var $table = 'relancesnonrespectssanctionseps93';
		var $import = array( 'table' => 'relancesnonrespectssanctionseps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'nonrespectsanctionep93_id' => '1',
				'numrelance' => '1',
				'dateecheance' => '2010-11-20',
				'dateimpression' => null,
				'daterelance' => '2010-10-5',
			)
		);
	}

?>
