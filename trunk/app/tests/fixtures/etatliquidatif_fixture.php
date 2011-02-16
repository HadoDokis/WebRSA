<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class EtatliquidatifFixture extends CakeAppTestFixture {
		var $name = 'Etatliquidatif';
		var $table = 'etatsliquidatifs';
		var $import = array( 'table' => 'etatsliquidatifs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => 1,
				'budgetapre_id' => 1,
				'entitefi' => 'a',
				'engagement' => null,
				'tiers' => 'a',
				'codecdr' => 'a',
				'libellecdr' => 'a',
				'natureanalytique' => 'a',
				'programme' => 'a',
				'lib_programme' => 'a',
				'apreforfait' => 'a',
				'aprecomplem' => null,
				'natureimput' => 'a',
				'typeapre' => 'forfaitaire',
				'operation' => 'a',
// 				'objet' => 'a',
				'datecloture' => null,
				'montanttotalapre' => null,
			)
		);
	}

?>

