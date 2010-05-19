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
		
		function testSoumisDroitsEtDevoirs() {
			$this->assertTrue($this->Personne->soumisDroitsEtDevoirs(1));
			$this->assertTrue($this->Personne->soumisDroitsEtDevoirs(4));
			$this->assertTrue($this->Personne->soumisDroitsEtDevoirs(5));
			$this->assertFalse($this->Personne->soumisDroitsEtDevoirs(6));
			$this->assertTrue($this->Personne->soumisDroitsEtDevoirs(10));
		}

	}
?>
