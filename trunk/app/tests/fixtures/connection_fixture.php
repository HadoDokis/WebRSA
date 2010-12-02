<?php

	class ConnectionFixture extends CakeTestFixture {
		var $name = 'Connection';
		var $table = 'connections';
		var $import = array( 'table' => 'connections', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'user_id' => '1',
				'php_sid' => null,
				'created' => null,
				'modified' => null,
			)
		);
	}

?>
