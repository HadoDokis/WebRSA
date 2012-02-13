<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ParametrefinancierFixture extends CakeAppTestFixture {
		var $name = 'Parametrefinancier';
		var $table = 'parametresfinanciers';
		var $import = array( 'table' => 'parametresfinanciers', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'entitefi' => 'ef',
				'engagement' => 'ef',
				'tiers' => 'ef',
				'codecdr' => 'ef',
				'libellecdr' => 'ef',
				'natureanalytique' => 'ef',
				'programme' => 'ef',
				'lib_programme' => 'ef',
				'apreforfait' => 'ef',
				'aprecomplem' => 'ef',
				'natureimput' => 'ef'
			),
		);
	}

?>
