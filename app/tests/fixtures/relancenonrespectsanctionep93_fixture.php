<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Relancenonrespectsanctionep93Fixture extends CakeAppTestFixture {
		var $name = 'Relancenonrespectsanctionep93';
		var $table = 'relancesnonrespectssanctionseps93';
		var $import = array( 'table' => 'relancesnonrespectssanctionseps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'nonrespectsanctionep93_id' => '1',
				'numrelance' => '1',
				'dateimpression' => null,
				'daterelance' => '2010-11-04',
			),
			array(
				'id' => '2',
				'nonrespectsanctionep93_id' => '2',
				'numrelance' => '1',
				'dateimpression' => null,
				'daterelance' => '2010-11-04',
			),
			array(
				'id' => '8',
				'nonrespectsanctionep93_id' => '9',
				'numrelance' => '1',
				'dateimpression' => null,
				'daterelance' => '2010-10-15',
			),
		);
	}

?>
