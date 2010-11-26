<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Propopdo');

	class PropopdoTestCase extends CakeAppModelTestCase {
		/*
		function testPrepare() {
			$result = $this->Propopdo->prepare();
			var_dump($result);
		}
		*/

		function testEtatPdo() {

		}

		function testBeforeSave() {
			$options = array(
					'id' => '1',
					'datedecisionpdo' => null,
					'commentairepdo' => null,
					'motifpdo' => null,
					'typepdo_id' => '1',
					'decisionpdo_id' => 1,
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
					'isvalidation' => null,
					'validationdecision' => null,
					'datevalidationdecision' => null,
					'isdecisionop' => null,
					'decisionop' => null,
					'datedecisionop' => null,
					'observationoop' => null,
					'etatdossierpdo' => null,
					);
			$result = $this->Propopdo->beforeSave($options);
			$this->assertTrue($result);
		}
	}
?>
