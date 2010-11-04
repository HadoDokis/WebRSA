<?php

	class UserFixture extends CakeTestFixture {
		var $name = 'User';
		var $table = 'users';
		var $import = array( 'table' => 'users', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>