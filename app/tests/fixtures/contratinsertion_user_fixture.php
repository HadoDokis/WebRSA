<?php

	class ContratinsertionUserFixture extends CakeTestFixture {
		var $name = 'ContratinsertionUser';
		var $table = 'contratsinsertion_users';
		var $import = array( 'table' => 'contratsinsertion_users', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'user_id' => '1',
				'contratinsertion_id' => '1',
				'id' => '1',
			),
		);
	}

?>
