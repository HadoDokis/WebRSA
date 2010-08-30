<?php

	class PropopdoFixture extends CakeTestFixture {
		var $name = 'Propopdo';
		var $table = 'propospdos';
		var $import = array( 'table' => 'propospdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'datedecisionpdo' => null,
				'commentairepdo' => null,
				'motifpdo' => null,
				'typepdo_id' => '3',
				'decisionpdo_id' => null,
				'typenotifpdo_id' => null,
				'datereceptionpdo' => null,
				'originepdo_id' => null,
				'choixpdo' => null,
				'dateenvoiop' => null,
				'daterevision' => null,
				'dateecheance' => null,
				'decision' => null,
				'suivi' => null,
				'autres' => null,
				'referent_id' => null,
				'nonadmis' => null,
				'categoriegeneral' => null,
				'categoriedetail' => null,
				'personne_id' => 1,
				'user_id' => 1,
			),
			array(
				'id' => '2',
				'datedecisionpdo' => null,
				'commentairepdo' => null,
				'motifpdo' => null,
				'typepdo_id' => '3',
				'decisionpdo_id' => null,
				'typenotifpdo_id' => null,
				'datereceptionpdo' => null,
				'originepdo_id' => null,
				'choixpdo' => null,
				'dateenvoiop' => null,
				'daterevision' => null,
				'dateecheance' => null,
				'decision' => null,
				'suivi' => null,
				'autres' => null,
				'referent_id' => null,
				'nonadmis' => null,
				'categoriegeneral' => null,
				'categoriedetail' => null,
				'personne_id' => 2,
				'user_id' => 2,
			),
		);
	}

?>
