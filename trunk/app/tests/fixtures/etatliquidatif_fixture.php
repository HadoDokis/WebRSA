<?php

	class EtatliquidatifFixture extends CakeTestFixture {
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
				'typeapre' => 'a',
				'operation' => 'a',
				'objet' => 'a',
				'datecloture' => null,
				'montanttotalapre' => null,
			)
		);
	}

?>

