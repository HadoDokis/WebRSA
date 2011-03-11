<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Referent');

	class ReferentTestCase extends CakeAppModelTestCase {

		function testListOptions() {
			$result=$this->Referent->listOptions();
			$expected=array(
				'2_2' => 'M Deufs John',
				'7_3' => 'M Lereferent Dddddd',
				'1_1' => 'M Némard Jean',
			);
			$this->assertEqual($result,$expected);
		}

		function testReferentsListe() {
			$result=$this->Referent->referentsListe(1);
			$expected=array(
				1 => 'M Némard Jean'
			);
			$this->assertEqual($result,$expected);

			//------------------------------------------------------------------

			$result=$this->Referent->referentsListe(2);
			$expected=array(
				2 => 'M Deufs John'
			);
			$this->assertEqual($result,$expected);

			//------------------------------------------------------------------

			$result=$this->Referent->referentsListe(666);
			$this->assertEqual(array(),$result);
		}

		function testReadByPersonneId() {
			$personne_id = '1';
			$result = $this->Referent->readByPersonneId($personne_id);
			$expected = array(
				'Referent' => array(
					'id' => '1',
					'structurereferente_id' => '1',
					'nom' => 'Némard',
					'prenom' => 'Jean',
					'numero_poste' => null,
					'email' => null,
					'qual' => 'M',
					'fonction' => null,
					'nom_complet' => 'M Némard Jean',
				),
			);
			$this->assertEqual($result, $expected);

			$personne_id = null;
			$result = $this->Referent->readByPersonneId($personne_id);
			$this->assertNull($result);
		}

	}
?>
