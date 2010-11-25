<?php

	class ModecontactFixture extends CakeTestFixture {
		var $name = 'Modecontact';
		var $table = 'modescontact';
		var $import = array( 'table' => 'modescontact', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'foyer_id' => '1',
				'numtel' => null,
				'numposte' => null,
				'nattel' => null,
				'matetel' => null,
				'autorutitel' => null,
				'adrelec' => null,
				'autorutiadrelec' => null,
			),
			array(
				'id' => '2',
				'foyer_id' => '2',
				'numtel' => null,
				'numposte' => null,
				'nattel' => null,
				'matetel' => null,
				'autorutitel' => null,
				'adrelec' => null,
				'autorutiadrelec' => null,
			),
		);
	}

?>
