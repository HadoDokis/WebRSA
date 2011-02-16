<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class CondadminFixture extends CakeAppTestFixture {
		var $name = 'Condadmin';
		var $table = 'condsadmins';
		var $import = array( 'table' => 'condsadmins', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'avispcgdroitrsa_id' => '1',
				'aviscondadmrsa' => null,
				'moticondadmrsa' => null,
				'comm1condadmrsa' => null,
				'comm2condadmrsa' => null,
				'dteffaviscondadmrsa' => null,
			)
		);
	}

?>
