<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Foyer');

	class FoyerTestCase extends CakeAppModelTestCase {

		/**
		* Test de la fonction dossierId
		*/
		function testDossierId() {
			// Foyer existant
			$result = $this->Foyer->dossierId(1);
			$this->assertEqual(1, $result);

			// Foyer non existant
			$result = $this->Foyer->dossierId(666);
			$this->assertNull($result);

			// foyer_id incoherent, insére "toto" à la place d'un id
			// $result = $this->Foyer->dossierId("toto");
			//$this->assertNull($result);

			// foyer_id incoherent
			$result = $this->Foyer->dossierId(-42);
			$this->assertNull($result);
		}

		/**
		* Test de la fonction nbEnfant
		*/
		function testNbEnfants() {
			// nombre d'enfant dans le foyer 1
			$result = $this->Foyer->nbEnfants(1);
			$this->assertEqual(1, $result);

			// nombre d'enfant dans le foyer 2
			$result = $this->Foyer->nbEnfants(2);
			$this->assertEqual(0, $result);

			// nombre d'enfant dans un foyer inexistant (foyer_id == 42)
			$result = $this->Foyer->nbEnfants(42);
			$this->assertEqual(0, $result);

			// nombre d'enfant dans un foyer inexistant (foyer_id == -42)
			$result = $this->Foyer->nbEnfants(-42);
			$this->assertEqual(0, $result);

			//FIXME nombre d'enfant dans un foyer inexistant, crée des exceptions
			//$result = $this->Foyer->nbEnfants("toto");
			//$this->assertNull($result);
		}

		/**
		* Test de la fonction montantForfaitaire
		*/

		function testMontantForfaitaire() {
			// test pour le foyer 1
			$result = $this->Foyer->montantForfaitaire(1);
			$this->assertTrue($result);

			// test pour le foyer 2
			$result = $this->Foyer->montantForfaitaire(2);
			$this->assertFalse($result);

			// test pour le foyer 3
			$result = $this->Foyer->montantForfaitaire(3);
			$this->assertTrue($result);

			// test pour le foyer 4
			$result = $this->Foyer->montantForfaitaire(4);
			$this->assertFalse($result);

			// test pour le foyer 5
			$result = $this->Foyer->montantForfaitaire(5);
			$this->assertFalse($result);

			///FIXME: renvoie vrai pour un foyer non existant c'est normal ???
			// test pour le foyer 666 (inexistant)
			$result = $this->Foyer->montantForfaitaire(666);
			$this->assertFalse($result);

			// test avec un foyer_id incoherent (-42)
			$result = $this->Foyer->montantForfaitaire(-42);
			$this->assertFalse($result);

			//FIXME test avec un foyer_id incoherent, insére "toto" à la place d'un id
			// $result = $this->Foyer->montantForfaitaire("toto");
			//$this->assertFalse($result);			
		}

		/**
		*
		*/
		function testRefreshRessources() {
			//retournera toujours vrai à moins que la connexion à la base de données soit interrompue
			$this->assertTrue($this->Foyer->refreshRessources(1));
		}

		/**
		*
		*/
		function testRefreshSoumisADroitsEtDevoirs() {
			//retournera toujours vrai à moins que la connexion à la base de données soit interrompue
			$this->assertTrue($this->Foyer->refreshSoumisADroitsEtDevoirs(1));
		}

		/**
		*
		*/

	}
?>
