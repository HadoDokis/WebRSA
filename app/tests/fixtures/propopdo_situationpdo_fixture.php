<?php

	class PropopdoSituationpdoFixture extends CakeTestFixture {
		var $name = 'PropopdoSituationpdo';
		var $table = 'propospdos_situationspdos';
		var $import = array( 'table' => 'propospdos_situationspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'situationpdo_id' => '1',
			)
		);
	}

?>
