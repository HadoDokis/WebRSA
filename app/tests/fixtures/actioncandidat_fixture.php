<?php

	class ActioncandidatFixture extends CakeTestFixture {
		var $name = 'Actioncandidat';
		var $table = 'actionscandidats';
		var $import = array( 'table' => 'actionscandidats', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'intitule' => 'intitulé',
				'code' => null,
			),
		);
	}

?>
