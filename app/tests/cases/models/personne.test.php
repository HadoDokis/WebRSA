<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Personne');

	class PersonneTestCase extends CakeAppModelTestCase {

		function testBeforeSave() {
			///FIXME: retournera toujours vrai
			$this->Personne->data=array(
				'Personne' => array(
					'id' => '1',
					'foyer_id' => '1',
					'qual' => 'MR',
					'nom' => 'Dupond',
					'prenom' => 'Azerty',
					'nomnai' => null,
					'prenom2' => null,
					'prenom3' => null,
					'nomcomnai' => null,
					'dtnai' => '1979-01-24',
					'rgnai' => '1',
					'typedtnai' => null,
					'nir' => null,
					'topvalec' => null,
					'sexe' => null,
					'nati' => null,
					'dtnati' => null,
					'pieecpres' => null,
					'idassedic' => null,
					'numagenpoleemploi' => null,
					'dtinscpoleemploi' => null,
					'numfixe' => null,
					'numport' => null,
				)
			);
			$this->assertTrue($this->Personne->beforeSave());
		}

		function testDossierId() {
			// test pour la personne 1
			$this->assertEqual(1,$this->Personne->dossierId(1));

			// test pour la personne 2
			$this->assertEqual(2,$this->Personne->dossierId(3));

			// test pour une personne inexistante
			$this->assertNull($this->Personne->dossierId(666));
		}
		
		function testSoumisDroitsEtDevoirs() {
			$personne_id = 1;// prestation -> ENF (ni CJT, ni DEM);
			$result = $this->Personne->soumisDroitsEtDevoirs($personne_id);
			$this->assertFalse($result);

			$personne_id = 5;
			$result = $this->Personne->soumisDroitsEtDevoirs($personne_id);
			$this->assertTrue($result);


			$personne_id = 4;//2 personnes dans le foyer mais les ressources ne sont pas suffisantes
			$result = $this->Personne->soumisDroitsEtDevoirs($personne_id);
			$this->assertTrue($result);
		}
		

		function testFindByZones() {
			$zonegeo = array('ighr8', 'pokf2');				
			$result = $this->Personne->findByZones($zonegeo, true);
			$expected = array(
				'0' => '1',
				'1' => '2',
				'2' => '3',
				'3' => '4',
			);
			$this->assertEqual($result, $expected);

			$zonegeo = array('93066');
			$result = $this->Personne->findByZones($zonegeo, true);
			$expected = array(
				'0' => '1001',
				'1' => '2002',
				'2' => '3003',
				'3' => '4004',
			);
			$this->assertEqual($result, $expected);
		}

		
		function testDetailsCi() {
			$personne_id = '1';
			$user_id = null;
			$result = $this->Personne->detailsCi($personne_id, $user_id);
			$this->assertTrue($result);

			$personne_id = '2';
			$user_id = null;
			$result = $this->Personne->detailsCi($personne_id, $user_id);
			$this->assertTrue($result);
		}

		function testDetailsApre() {
			$personne_id = '1';
			$user_id = null;
			$result = $this->Personne->detailsApre($personne_id, $user_id);
			$this->assertTrue($result);

			$personne_id = '2';
			$user_id = null;
			$result = $this->Personne->detailsApre($personne_id, $user_id);
			$this->assertTrue($result);

			//FIXME renvoi un array et engendre des erreurs avec personne_id = 1337
			// test personne_id == 1337 (inexistant)
			//$result = $this->Personne->detailsApre(1337, null);
			//$this->assertFalse($result);
			//$this->assertNull($result);
		}

		

		function testNewDetailsCi() {
			$personne_id = '1';
			$user_id = null;
			$result = $this->Personne->newDetailsCi($personne_id, $user_id);
			$this->assertFalse($result['0']);

			$personne_id = '2';
			$user_id = null;
			$result = $this->Personne->newDetailsCi($personne_id, $user_id);
			$this->assertEqual($result['Personne']['nom'], 'Dupond');

			$personne_id = '1337'; //(inexistant)
			$user_id = null;
			$result = $this->Personne->newDetailsCi($personne_id, $user_id);
			$this->assertFalse($result);
		}
		
	}
?>
