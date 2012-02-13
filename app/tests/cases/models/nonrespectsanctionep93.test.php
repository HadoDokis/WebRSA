<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Nonrespectsanctionep93');

	class Nonrespectsanctionep93TestCase extends CakeAppModelTestCase {

		function testVerrouiller() {
			$commissionep_id = null;
			$etape = null;
			$this->assertTrue($this->Nonrespectsanctionep93->verrouiller($commissionep_id, $etape));
		}

		function testQdDossiersParListe() {
			$commissionep_id = '3';
			$niveauDecision = 'cg';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($commissionep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.commissionep_id'], $commissionep_id);

			$niveauDecision = 'ep';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($commissionep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.commissionep_id'], $commissionep_id);

			$commissionep_id = '6';
			$niveauDecision = 'cg';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($commissionep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.commissionep_id'], $commissionep_id);

			$niveauDecision = 'ep';
			$return = $this->Nonrespectsanctionep93->qdDossiersParListe($commissionep_id, $niveauDecision);
			$this->assertFalse(empty($return));
			$this->assertEqual($return['conditions']['Dossierep.themeep'], 'nonrespectssanctionseps93');
			$this->assertEqual($return['conditions']['Dossierep.commissionep_id'], $commissionep_id);
		}

		function testPrepareFormData() {
			$commissionep_id = '3';
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
			$return = $this->Nonrespectsanctionep93->prepareFormData($commissionep_id, $datas, $niveauDecision);
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
			$niveauDecision = 'cg';
			$result = $this->Nonrespectsanctionep93->saveDecisions($data, $niveauDecision);
			//debug($this->Decisionnonrespectsanctionep93);
			$this->assertTrue($result);
		}

		function testSaveDecisionUnique() {
			$data = array();
			$niveauDecision = 'cg';
			$this->assertTrue($this->Nonrespectsanctionep93->saveDecisionUnique($data, $niveauDecision));
		}

		function testFinaliser() {
			$commissionep_id = null;
			$etape = null;
			$result = $this->Nonrespectsanctionep93->finaliser($commissionep_id, $etape);
			$this->assertTrue($result);
		}

		function testContainPourPv() {
			$expected = array(
				'Nonrespectsanctionep93' => array(
					'Decisionnonrespectsanctionep93' => array(
						/*'fields' => array(
							'( CAST( decision AS TEXT ) || montantreduction ) AS avis'
						),*/
						'conditions' => array(
							'etape' => 'ep'
						),
					)
				),
			);
			$result = $this->Nonrespectsanctionep93->containPourPv();
			$this->assertEqual($result, $expected);
		}

		function testQdProcesVerbal() {
			$expected = array(
				'fields' => array(
					'Nonrespectsanctionep93.id',
					'Nonrespectsanctionep93.dossierep_id',
					'Nonrespectsanctionep93.propopdo_id',
					'Nonrespectsanctionep93.orientstruct_id',
					'Nonrespectsanctionep93.contratinsertion_id',
					'Nonrespectsanctionep93.origine',
					'Nonrespectsanctionep93.rgpassage',
					'Nonrespectsanctionep93.sortienvcontrat',
					'Nonrespectsanctionep93.active',
					'Nonrespectsanctionep93.created',
					'Nonrespectsanctionep93.modified',
					'Decisionnonrespectsanctionep93.id',
					'Decisionnonrespectsanctionep93.nonrespectsanctionep93_id',
					'Decisionnonrespectsanctionep93.etape',
					'Decisionnonrespectsanctionep93.decision',
					'Decisionnonrespectsanctionep93.montantreduction',
					'Decisionnonrespectsanctionep93.dureesursis',
					'Decisionnonrespectsanctionep93.commentaire',
					'Decisionnonrespectsanctionep93.created',
					'Decisionnonrespectsanctionep93.modified',
				),
				'joins' => array(
					array(
						'table'      => 'nonrespectssanctionseps93',
						'alias'      => 'Nonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionsnonrespectssanctionseps93',
						'alias'      => 'Decisionnonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionnonrespectsanctionep93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id',
							'Decisionnonrespectsanctionep93.etape' => 'ep'
						),
					),
				)
			);
			$result = $this->Nonrespectsanctionep93->qdProcesVerbal();
			$this->assertEqual($result, $expected);
		}

	}

?>
