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

			// test pour une personne inexistente
			$this->assertNull($this->Personne->dossierId(666));
		}
		/*
		function testSoumisDroitsEtDevoirs() {
			//test avec personne_id == 1
			$result = $this->Personne->soumisDroitsEtDevoirs(1);
			$this->assertTrue($result);
			//test avec personne_id == 1337
			$result = $this->Personne->soumisDroitsEtDevoirs(1337);
			$this->assertFalse($result);
			//test avec personne_id == -42
			$result = $this->Personne->soumisDroitsEtDevoirs(-42);
			$this->assertFalse($result);
		}
		*/

		function testFindByZones() {
			$zonegeo = array(
					array(
						'id' => '1',
						'codeinsee' => '34090',
						'libelle' => 'Pole Montpellier-Nord',
					),
					array(
						'id' => '2',
						'codeinsee' => '34070',
						'libelle' => 'Pole Montpellier Sud-Est',
					),
					array(
						'id' => '3',
						'codeinsee' => '34080',
						'libelle' => 'Pole Montpellier Ouest',
					)
				);				
			$result = $this->Personne->findByZones($zonegeo, null);
			$expected = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "667");
			$this->assertEqual($result, $expected);
		}

		/*
		function testDetailsCi() {
			//test personne_id == 1
			$result = $this->Personne->detailsCi(1, null);
			$this->assertTrue($result);

			//test personne_id == 2
			$result = $this->Personne->detailsCi(2, null);
			$this->assertTrue($result);

			//FIXME renvoi un array et engendre des erreurs avec personne_id = 1337
			// test personne_id == 1337 (inexistant)
			//$result = $this->Personne->detailsCi(1337, null);
			//$this->assertFalse($result);
			//$this->assertNull($result);
		}

		function testDetailsApre() {
			//test personne_id == 1
			$result = $this->Personne->detailsApre(1, null);
			$this->assertTrue($result);

			//test personne_id == 2
			$result = $this->Personne->detailsApre(2, null);
			$this->assertTrue($result);

			//FIXME renvoi un array et engendre des erreurs avec personne_id = 1337
			// test personne_id == 1337 (inexistant)
			//$result = $this->Personne->detailsApre(1337, null);
			//$this->assertFalse($result);
			//$this->assertNull($result);
		}

		function testNewDetailsCi() {
			//test personne_id == 1
			$result = $this->Personne->newDetailsCi(1, null);
			$this->assertTrue($result);

			//test personne_id == 2
			$result = $this->Personne->newDetailsCi(2, null);
			$this->assertFalse($result);

			// test personne_id == 1337 (inexistant)
			$result = $this->Personne->newDetailsCi(1337, null);
			$this->assertFalse($result);
		}
		*/
	}
?>
