<?php

	class JetonFixture extends CakeTestFixture {
		var $name = 'Jeton';
		var $table = 'jetons';
		var $import = array( 'table' => 'jetons', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossier_id' => '1',
				'php_sid' => null,
				'user_id' => '1',
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
