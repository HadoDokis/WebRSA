<?php

	class JetonfonctionFixture extends CakeTestFixture {
		var $name = 'Jetonfonction';
		var $table = 'jetonsfonctions';
		var $import = array( 'table' => 'jetonsfonctions', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'controller' => 'controller',
				'action' => 'action',
				'php_sid' => null,
				'user_id' => '1',
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
