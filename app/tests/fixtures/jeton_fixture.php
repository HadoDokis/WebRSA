<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class JetonFixture extends CakeAppTestFixture {
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
