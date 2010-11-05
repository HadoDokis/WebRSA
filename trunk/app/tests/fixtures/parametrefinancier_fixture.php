<?php

	class ParametrefinancierFixture extends CakeTestFixture {
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
