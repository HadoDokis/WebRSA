<?php

	class PropopdoStatutpdoFixture extends CakeTestFixture {
		var $name = 'PropopdoStatutpdo';
		var $table = 'propospdos_statutspdos';
		var $import = array( 'table' => 'propospdos_statutspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'statutpdo_id' => '1',
			)
		);
	}

?>
