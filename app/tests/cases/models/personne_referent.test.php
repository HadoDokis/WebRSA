<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'PersonneReferent');

	class PersonneReferentTestCase extends CakeAppModelTestCase {

		function testDossierId() {
			$result=$this->PersonneReferent->dossierId(1);
			$this->assertEqual(1,$result);

			//------------------------------------------------------------------

			$result=$this->PersonneReferent->dossierId(3);
			$this->assertEqual(2,$result);

			//------------------------------------------------------------------

			$result=$this->PersonneReferent->dossierId(666);
			$this->assertNull($result);

			$result = $this->PersonneReferent->dossierId(-42);
			$this->assertNull($result);
		}
		/*
		function testBeforeSave() {
			$this->PersonneReferent->data=array(
				'PersonneReferent' => array(
					'id' => '1',
					'personne_id' => '1',
					'referent_id' => '1',
					'dddesignation' => null,
					'dfdesignation' => null,
					'structurereferente_id' => '1',
				)
			);
			$result = $this->PersonneReferent->beforeSave(array());
			$this->assertTrue($result);
		}

		function testSqDerniere() {
			$result = $this->PersonneReferent->sqDerniere(1);

			// remplacement de "{$table}" par "personnes_referents"
			// on retrouve 1 (parametre de sqDerniere(1) dans la requete)
			$expected = "
				SELECT personnes_referents.id
					FROM personnes_referents
					WHERE
						personnes_referents.personne_id = 1
					ORDER BY personnes_referents.dddesignation DESC
					LIMIT 1
			";
			$this->assertEqual($expected, $result);
		}
		*/
	}
?>
