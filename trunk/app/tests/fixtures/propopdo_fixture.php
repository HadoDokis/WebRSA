<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PropopdoFixture extends CakeAppTestFixture {
		var $name = 'Propopdo';
		var $table = 'propospdos';
		var $import = array( 'table' => 'propospdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				//'datedecisionpdo' => null,
				//'commentairepdo' => null,
				'motifpdo' => null,
				'typepdo_id' => '1',
				//'decisionpdo_id' => 1,
				'typenotifpdo_id' => 1,
				'datereceptionpdo' => null,
				'originepdo_id' => 1,
				'choixpdo' => null,
				'dateenvoiop' => null,
				'daterevision' => null,
				'dateecheance' => null,
				'decision' => null,
				'suivi' => null,
				'autres' => null,
				'referent_id' => 1,
				'nonadmis' => null,
				'categoriegeneral' => null,
				'categoriedetail' => null,
				'personne_id' => 1,
				'user_id' => 1,
				'structurereferente_id' => 1,
				'iscomplet' => null,
				//'isvalidation' => null,
				//'validationdecision' => null,
				//'datevalidationdecision' => null,
				'isdecisionop' => null,
				'decisionop' => null,
				'datedecisionop' => null,
				'observationoop' => null,
				'serviceinstructeur_id' => '1', //++
				'created' => null, //++
				'modified' => null, //++
				'etatdossierpdo' => null,
				'orgpayeur' => null, //++
			),
		);
	}

?>
