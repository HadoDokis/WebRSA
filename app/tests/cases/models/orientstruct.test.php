<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Orientstruct');

	class OrientstructTestCase extends CakeAppModelTestCase {

		function testDossierId() {
			$result=$this->Orientstruct->dossierId(1);
			$this->assertEqual(1,$result);

			//------------------------------------------------------------------

			$result=$this->Orientstruct->dossierId(3);
			$this->assertEqual(2,$result);

			//------------------------------------------------------------------

			$result=$this->Orientstruct->dossierId(666);
			$this->assertNull($result);
		}

		function testChoixStructure() {
			$field = null;
			$compare_field = null;
			$result = $this->Orientstruct->choixStructure($field, $compare_field);
			$this->assertTrue($result);
		}

		function testGetDataPdf() {
			$id = '1';
			$user_id = '1';
			$result = $this->Orientstruct->getDataPdf($id, $user_id);
			$this->assertFalse($result);
		}

		function testFillAllocataire() {
			$result = $this->Orientstruct->fillAllocataire();
			/*
			$expected = "INSERT INTO orientsstructs (personne_id, statut_orient)
					(
						SELECT DISTINCT personnes.id, 'Non orienté' AS statut_orient
							FROM personnes
								INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = 'RSA' AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' ) )
							WHERE personnes.id NOT IN (
								SELECT orientsstructs.personne_id
									FROM orientsstructs
							)
					);";
			$this->assertEqual($result, $expected);
			*/
			var_dump($result);
		}
	}
?>
