<?php

	class AidedirecteFixture extends CakeTestFixture {
		var $name = 'Aidedirecte';
		var $table = 'aidesdirectes';
		var $import = array( 'table' => 'aidesdirectes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'actioninsertion_id' => '1',
				'lib_aide' => null,
				'typo_aide' => null,
				'date_aide' => null,
			),
		);
	}

?>
