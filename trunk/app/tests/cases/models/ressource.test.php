<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Ressource');

	class RessourceTestCase extends CakeAppModelTestCase
	{

		/**
		* Test de la fonction dossierId
		*/
		function testDossierId() {
			// Ressource existante
			$result = $this->Ressource->dossierId(1);
			$this->assertEqual(1, $result);

			// Ressource inexistante
			$result = $this->Ressource->dossierId(666);
			$this->assertNull($result);

			// Ressource existante mais pas la personne
			$result = $this->Ressource->dossierId(555);
			$this->assertNull($result);		

			// Ressource existante, la personne aussi mais pas le foyer
			$result = $this->Ressource->dossierId(556);
			$this->assertNull($result);		
		}

		function testAfterFind() {
			$results = array(
				array(
					'id' => '1',
					'personne_id' => '1',
					'topressnul' => '1',
					'mtpersressmenrsa' => '0.00',
					'ddress' => '2005-06-11',
					'dfress' => '2005-08-14',
				),
				array(
					'id' => '2',
					'personne_id' => '3',
					'topressnul' => '0',
					'mtpersressmenrsa' => '1000.00',
					'ddress' => '2006-05-07',
					'dfress' => '2006-07-10',
				),
				array(
					'id' => '3',
					'personne_id' => '5',
					'topressnul' => '0',
					'mtpersressmenrsa' => '22.00',
					'ddress' => '2006-05-07',
					'dfress' => '2006-07-10',
				),
				array(
					'id' => '4',
					'personne_id' => '6',
					'topressnul' => '0',
					'mtpersressmenrsa' => '1200.00',
					'ddress' => '2006-05-07',
					'dfress' => '2006-07-10',
				),
				array(
					'id' => '5',
					'personne_id' => '9',
					'topressnul' => '0',
					'mtpersressmenrsa' => '1750.30',
					'ddress' => '2006-05-07',
					'dfress' => '2006-07-10',
				),
				array(
					'id' => '555',
					'personne_id' => '666',
					'topressnul' => '0',
					'mtpersressmenrsa' => '1000.00',
					'ddress' => '2006-05-07',
					'dfress' => '2006-07-10',
				),
				array(
					'id' => '556',
					'personne_id' => '667',
					'topressnul' => '0',
					'mtpersressmenrsa' => '1000.00',
					'ddress' => '2006-05-07',
					'dfress' => '2006-07-10',
				),
			);
			$primary = false;
			$result = $this->Ressource->afterFind($results, $primary = false);
			$this->assertTrue($result);
		}

		function testMoyenne() {
			$ressource = array(
				'id' => '1',
				'personne_id' => '1',
				'topressnul' => '1',
				'mtpersressmenrsa' => '0.00',
				'ddress' => '2005-06-11',
				'dfress' => '2005-08-14',
			);
			$result = $this->Ressource->moyenne($ressource);
			$this->assertEqual(0, $result);
		}

		function testRefresh() {
			$personne_id = 1;
			$result = $this->Ressource->refresh($personne_id);
			$this->assertTrue($result);

		}

		function testBeforeSave() {
			$options = null;
			$result = $this->Ressource->beforeSave($options = array());
			$this->assertTrue($result);
		}

		function testAfterSave() {
			$created = null;
			$result = $this->Ressource->afterSave($created);
			$this->assertNull($result);
		}

	}
?>
