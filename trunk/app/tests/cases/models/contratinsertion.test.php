<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Contratinsertion');

	class ContratinsertionTestCase extends CakeAppModelTestCase {

		// test fonction beforesave()
		function testBeforeSave() {
			$result = $this->Contratinsertion->beforeSave($option=array());
			$this->assertTrue($result);
		}

		// test fonction valider()
		function testValider() {
			$data = array();
			$result = $this->Contratinsertion->valider($data);
			debug($result);

			$data = array(
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
			$result = $this->Contratinsertion->valider($data);
			debug($result);
		}
 
		// test fonction aftersave()
		function testAfterSave() {
			$created = false;
			$result = $this->Contratinsertion->afterSave($created);
			$this->assertFalse($result);

		}

	}

?>
