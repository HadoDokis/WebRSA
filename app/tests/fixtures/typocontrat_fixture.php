<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TypocontratFixture extends CakeAppTestFixture {
		var $name = 'Typocontrat';
		var $table = 'typoscontrats';
		var $import = array( 'table' => 'typoscontrats', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '4',
				'lib_typo' => 'Renouvellement',
			),
			array(
				'id' => '5',
				'lib_typo' => 'Redéfinition',
			),
			array(
				'id' => '1',
				'lib_typo' => 'Premier contrat',
			),
		);
	}

?>
