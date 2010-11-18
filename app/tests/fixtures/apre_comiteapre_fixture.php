<?php

	class ApreComiteapreFixture extends CakeTestFixture {
		var $name = 'ApreComiteapre';
		var $table = 'apres_comitesapres';
		var $import = array( 'table' => 'apres_comitesapres', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'apre_id' => '1',
				'comiteapre_id' => '1',
				'montantattribue' => 700,
				'observationcomite' => null,
				'decisioncomite' => null,
				'recoursapre' => null,
				'observationrecours' => null,
				'daterecours' => null,
				'comite_pcd_id' => null,
			),
			array(
				'id' => '2',
				'apre_id' => '2',
				'comiteapre_id' => '2',
				'montantattribue' => 700,
				'observationcomite' => null,
				'decisioncomite' => null,
				'recoursapre' => null,
				'observationrecours' => null,
				'daterecours' => null,
				'comite_pcd_id' => null,
			),
			array(
				'id' => '3',
				'apre_id' => '3',
				'comiteapre_id' => '3',
				'montantattribue' => 700,
				'observationcomite' => null,
				'decisioncomite' => null,
				'recoursapre' => null,
				'observationrecours' => null,
				'daterecours' => null,
				'comite_pcd_id' => null,
			),
		);
	}

?>
