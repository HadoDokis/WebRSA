<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Nonrespectsanctionep93');

	class Nonrespectsanctionep93TestCase extends CakeAppModelTestCase {

		function testVerrouiller() {
			$seanceep_id = null;
			$etape = null;
			$this->assertTrue($this->Nonrespectsanctionep93->verrouiller($seanceep_id, $etape));
		}

		function testQdDossiersParListe() {
			$seanceep_id = '3';
			$niveauDecision = 'cg';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($seanceep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.seanceep_id'], $seanceep_id);

			$niveauDecision = 'ep';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($seanceep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.seanceep_id'], $seanceep_id);

			$seanceep_id = '6';
			$niveauDecision = 'cg';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($seanceep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.seanceep_id'], $seanceep_id);

			$niveauDecision = 'ep';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($seanceep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.seanceep_id'], $seanceep_id);
		}

		function testPrepareFormData() {
			$seanceep_id = '3';
			$datas = array(
				'1' => array(
					'id' => '1',
					'dossierep_id' => '1',
					'propopdo_id' => null,
					'orientstruct_id' => '1001',
					'contratinsertion_id' => '10',
					'origine' => 'orientstruct',
					'decision' => null,
					'rgpassage' => '1',
					'montantreduction' => null,
					'dureesursis' => null,
					'sortienvcontrat' => 0,
					'active' => 1,
					'created' => '2010-11-04',
					'modified' => '2010-11-04',
				),
				'2' => array(
					'id' => '2',
					'dossierep_id' => '2',
					'propopdo_id' => null,
					'orientstruct_id' => '2002',
					'contratinsertion_id' => '11',
					'origine' => 'orientstruct',
					'decision' => null,
					'rgpassage' => '1',
					'montantreduction' => null,
					'dureesursis' => null,
					'sortienvcontrat' => 0,
					'active' => 1,
					'created' => '2010-11-04',
					'modified' => '2010-11-04',
				),
			);
			$niveauDecision = 'ep';
			$return = $this->Nonrespectsanctionep93->prepareFormData($seanceep_id, $datas, $niveauDecision);
			$this->assertNotNull($return);
		}


		function testSaveDecisions() {
			$data = array(
				'Decisionnonrespectsanctionep93' => array(
					'id' => '1',
					'nonrespectsanctionep93_id' => '1',
					'etape' => 'cg',
					'decision' => '1reduction',
					'montantreduction' => '1337',
					'dureesursis' => null,
					'commentaire' => null,
					'created' => null,
					'modified' => null,
				),
				'Nonrespectsanctionep93' => array(
					'id' => '1',
					'dossierep_id' => '1',
					'propopdo_id' => null,
					'orientstruct_id' => '1001',
					'contratinsertion_id' => '10',
					'origine' => 'orientstruct',
					'decision' => null,
					'rgpassage' => '1',
					'montantreduction' => null,
					'dureesursis' => null,
					'sortienvcontrat' => 0,
					'active' => 1,
					'created' => '2010-11-04',
					'modified' => '2010-11-04',
				),
			);
			$niveauDecision = 'ep';
			$result = $this->Nonrespectsanctionep93->saveDecisions($data, $niveauDecision);
			debug($result);
		}

		function testFinaliser() {
			$seanceep_id = null;
			$etape = null;
			$this->Nonrespectsanctionep93->finaliser($seanceep_id, $etape);
		}

		function testContainPourPv() {
			$this->Nonrespectsanctionep93->containPourPv();
		}

		function testQdProcesVerbal() {
			$this->Nonrespectsanctionep93->qdProcesVerbal();
		}

	}

?>
