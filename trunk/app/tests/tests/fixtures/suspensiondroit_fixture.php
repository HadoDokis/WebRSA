<?php

	class SuspensiondroitFixture extends CakeTestFixture {
		var $name = 'Suspensiondroit';
		var $table = 'suspensionsdroits';
		var $import = array( 'table' => 'suspensionsdroits', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'situationdossierrsa_id' => '1',
				'motisusdrorsa' => null,
				'ddsusdrorsa' => '2009-01-21',
			),
			array(
				'id' => '2',
				'situationdossierrsa_id' => '2',
				'motisusdrorsa' => null,
				'ddsusdrorsa' => '2009-03-11',
			),
			array(
				'id' => '3',
				'situationdossierrsa_id' => '3',
				'motisusdrorsa' => null,
				'ddsusdrorsa' => '2009-03-15',
			),
			array(
				'id' => '4',
				'situationdossierrsa_id' => '4',
				'motisusdrorsa' => null,
				'ddsusdrorsa' => '2010-02-21',
			),
			array(
				'id' => '5',
				'situationdossierrsa_id' => '5',
				'motisusdrorsa' => null,
				'ddsusdrorsa' => '2009-02-01',
			),
		);
	}

?>
