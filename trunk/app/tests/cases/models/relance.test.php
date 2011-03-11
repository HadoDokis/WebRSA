<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Relance');

	class RelanceTestCase extends CakeAppModelTestCase {

		function testMountComparator() {
			$data = null;
			$this->Relance->data = array(
				'Relance' => array(
					'compare' => '1337',
					'nbjours' => '1337',
				),
			);
			$result = $this->Relance->mountComparator($data);
			$this->assertFalse($result);
		}

		function testQuerydata() {
			$type = 'gedooo';
			$params = array(
				'Jetons' => array(
					'id' => '2',
					'dossier_id' => '1001',
					'php_sid' => null,
					'user_id' => '5',
					'created' => null,
					'modified' => null,
				),
				'User' => array(
					'id' => '5',
					'group_id' => '1',
					'serviceinstructeur_id' => '3',
					'username' => 'cg93',
					'password' => 'ac860f0d3f51874b31260b406dc2dc549f4c6cde',
					'nom' => 'cg93',
					'prenom' => 'cg93',
					'date_naissance' => '1977-01-02',
					'date_deb_hab' => '2009-01-01',
					'date_fin_hab' => '2020-12-31',
					'numtel' => '0466666666',
					'filtre_zone_geo' => false,
					'numvoie' => null,
					'typevoie' => null,
					'nomvoie' => null,
					'compladr' => null,
					'codepos' => '93200',
					'ville' => 'ST DENIS',
					'isgestionnaire' => null,
					'sensibilite' => null,
				),
			);
			$result = $this->Relance->querydata($type, $params);
			$this->assertNotNull($result['fields']['0']);
			$this->assertNotNull($result['fields']['1']);
			$this->assertNotNull($result['joins']['0']);
		}

		function testSearch() {
			$statutRelance = 'Relance::arelancer';
			$mesCodesInsee = array('93066');
			$filtre_zone_geo = true; 
			$criteresrelance = null;
			$lockedDossiers = null;
			$result = $this->Relance->search($statutRelance, $mesCodesInsee, $filtre_zone_geo, $criteresrelance, $lockedDossiers);
			$this->assertEqual($result['conditions']['3'], 'Adresse.numcomptt IN ( \'93066\' )');

		}

	}

?>
