<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Dossierep');

	class DossierepTestCase extends CakeAppModelTestCase {

		function testThemeTraite() {
			$id = '1';
			$expected = array(
				'nonrespectsanctionep93' => 'cg',
				//'reorientationep93' => 'cg',
			);
			$result = $this->Dossierep->themeTraite($id);
			$this->assertEqual($result, $expected);
		}

		function testPrepareFormDataUnique() {
			$dossierep_id = '1';
			$dossier = array (
				'id' => '1001',
				'numdemrsa' => '13371001',
				'dtdemrsa' => '2009-01-01',
				'dtdemrmi' => null,
				'numdepinsrmi' => null,
				'typeinsrmi' => null,
				'numcominsrmi' => null,
				'numagrinsrmi' => null,
				'numdosinsrmi' => null,
				'numcli' => null,
				'numorg' => 931,
				'fonorg' => 'CAF',
				'matricule' => '930100100000000',
				'statudemrsa' => null,
				'typeparte' => 'CG',
				'ideparte' => '093',
				'fonorgcedmut' => null,
				'numorgcedmut' => null,
				'matriculeorgcedmut' => null,
				'ddarrmut' => null,
				'codeposanchab' => null,
				'fonorgprenmut' => null,
				'numorgprenmut' => null,
				'dddepamut' => null,
				'detaildroitrsa_id' => null,
				'avispcgdroitrsa_id' => null,
				'organisme_id' => null
			);
			$niveauDecision = 'cg';
			$result = $this->Dossierep->prepareFormDataUnique($dossierep_id, $dossier, $niveauDecision);
			debug($result);
		}

		function testSauvegardeUnique() {
			$dossierep_id = '1';
			$dossier = array (
				'id' => '1001',
				'numdemrsa' => '13371001',
				'dtdemrsa' => '2009-01-01',
				'dtdemrmi' => null,
				'numdepinsrmi' => null,
				'typeinsrmi' => null,
				'numcominsrmi' => null,
				'numagrinsrmi' => null,
				'numdosinsrmi' => null,
				'numcli' => null,
				'numorg' => 931,
				'fonorg' => 'CAF',
				'matricule' => '930100100000000',
				'statudemrsa' => null,
				'typeparte' => 'CG',
				'ideparte' => '093',
				'fonorgcedmut' => null,
				'numorgcedmut' => null,
				'matriculeorgcedmut' => null,
				'ddarrmut' => null,
				'codeposanchab' => null,
				'fonorgprenmut' => null,
				'numorgprenmut' => null,
				'dddepamut' => null,
				'detaildroitrsa_id' => null,
				'avispcgdroitrsa_id' => null,
				'organisme_id' => null
			);
			$niveauDecision = 'cg';
			$result = $this->Dossierep->sauvegardeUnique($dossierep_id, $dossier, $niveauDecision);
			$this->assertNotNull($result);
		}

		function testErreursCandidatePassage() {
			$personne_id = '1001';
			$result = $this->Dossierep->erreursCandidatePassage($personne_id);
			$expected = array();
			$this->assertIdentical($result, $expected);

			$personne_id = '2002';
			$result = $this->Dossierep->erreursCandidatePassage($personne_id);
			$expected = array(
				'0' => 'Situationdossierrsa.etatdosrsa',
			);// Situationdossierrsa -> droit clos sur l'etat dossier
			$this->assertIdentical($result, $expected);

			$personne_id = '3003';
			$result = $this->Dossierep->erreursCandidatePassage($personne_id);
			$expected = array(
				'0' => 'Prestation.rolepers'
			);// La personne n'est ni demandeur, ni conjoint, mais ENFant
			$this->assertIdentical($result, $expected);
		}

	}
?>
