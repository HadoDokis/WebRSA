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
			$this->Orientstruct->data = array(
				'Orientstruct' => array(
					'id' => '1',
					'personne_id' => '2',
					'typeorient_id' => '1',
					'structurereferente_id' => '1',
					'propo_algo' => 1,
					'valid_cg' => null,
					'date_propo' => '2009-03-10',
					'date_valid' => '2009-03-10',
					'statut_orient' => 'Orienté',
					'date_impression' => '2010-06-03',
					'daterelance' => null,
					'statutrelance' => 'E',
					'date_impression_relance' => null,
					'referent_id' => '1',
					'etatorient' => null,
					'rgorient' => null,
					'structureorientante_id' => null,
					'referentorientant_id' => null,
				),
			);
			$field = $this->Orientstruct->data;
			$compare_field = 'statutrelance';
			$result = $this->Orientstruct->choixStructure($field, $compare_field);
			$this->assertTrue($result);
		}

		function testModeleOdt() {
			$data = null;
			$this->Orientstruct->modeleOdt($data);
		}

		function testGetDataForPdf() {
			$id = '1';
			$user_id = '1';
			$result = $this->Orientstruct->getDataForPdf($id);
			$this->assertTrue($result);
		}

		function testFillAllocataire() {
			$this->Orientstruct->data = array();
			$result = $this->Orientstruct->fillAllocataire();
			$this->assertFalse($result);
		}

		function testRgorientMax() {
			$personne_id = '1';
			$result = $this->Orientstruct->rgorientMax($personne_id);
		}
/*
		function testAjoutPossible() {
			$personne_id = null;
			$result = $this->Orientstruct->ajoutPossible($personne_id);
		}

		function testIsRegression() {
			$personne_id = null;
			$newtypeorient_id = null;
			$result = $this->Orientstruct->isRegression($personne_id, $newtypeorient_id);
		}

		function testBeforeSave() {
			$options = array();
			$result = $this->Orientstruct->beforeSave($option);
		}
*/
	}
?>
