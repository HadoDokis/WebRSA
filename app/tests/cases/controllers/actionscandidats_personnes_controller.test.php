<?php
	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'ActionscandidatsPersonnes');
	class TestActionscandidatsPersonnesController extends ActionscandidatsPersonnesController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='ActionscandidatsPersonnes';

		public function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
			$this->redirectStatus = $status;
		}

		public function render($action = null, $layout = null, $file = null) {
			$this->renderedAction = $action;
			$this->renderedLayout = (is_null($layout) ? $this->layout : $layout);
			$this->renderedFile = $file;
		}

		public function _stop($status = 0) {
			$this->stopped = $status;
		}

		public function assert( $condition, $error = 'error500', $parameters = array() ) {
			$this->condition = $condition;
			$this->error = $error;
			$this->parameters = $parameters;
		}

		// Attention on surcharge la visibilite du parent
		function _setOptions() {
			return parent::_setOptions();
		}

		function _add_edit() {
			return parent::_add_edit();
		}
	}

	class ActionscandidatsPersonnesControllerTest extends CakeAppControllerTestCase {

		function testSetOptions() {
			$this->assertEqual($this->ActionscandidatsPersonnesController->viewVars, array());
			$this->ActionscandidatsPersonnesController->_setOptions();
			$this->assertNotNull($this->ActionscandidatsPersonnesController->viewVars);
		}

		function testIndexparams(){
			$this->ActionscandidatsPersonnesController->params['form']['Cancel'] = '12345';
			$this->ActionscandidatsPersonnesController->indexparams();
			$this->assertEqual(array('controller' =>'parametrages','action' =>'index'),$this->ActionscandidatsPersonnesController->redirectUrl);
		}
/*
		function testIndex() {

			$personne_id = 1;
			$dossier_id = 1;
			$this->ActionscandidatsPersonnesController->index($personne_id);
			$this->assertEqual($dossier_id, $this->ActionscandidatsPersonnesController->viewVars['dossierId']);

			$personne_id = 1337;
			$dossier_id = null;
			$this->ActionscandidatsPersonnesController->index($personne_id);
			$this->assertEqual($dossier_id, $this->ActionscandidatsPersonnesController->viewVars['dossierId']);
		}

		function testAjaxpart() {
			$actioncandidat_id = 1;
			$expected = array();
			$this->ActionscandidatsPersonnesController->ajaxpart($actioncandidat_id);
			$this->assertNotNull($this->ActionscandidatsPersonnesController->viewVars['part']);
			$this->assertNotEqual($expected, $this->ActionscandidatsPersonnesController->viewVars['part']);
			$this->assertNotNull($this->ActionscandidatsPersonnesController->viewVars['parts']);
			$this->assertNotNull($this->ActionscandidatsPersonnesController->viewVars['contact']);

			$actioncandidat_id = null;
			$this->ActionscandidatsPersonnesController->ajaxpart($actioncandidat_id);
			$this->assertEqual($expected, $this->ActionscandidatsPersonnesController->viewVars['part']);

		}

		function testAjaxstruct() {

			$referent_id = 1;
			$this->ActionscandidatsPersonnesController->ajaxstruct($referent_id);
			$la_structure_referente_id = 1;
			$this->assertEqual($la_structure_referente_id, $this->ActionscandidatsPersonnesController->viewVars['structs']['Structurereferente']['id']);
			$this->assertEqual($referent_id, $this->ActionscandidatsPersonnesController->viewVars['referent']['Referent']['id']);

			$referent_id = 1337;
			$expected = array();
			$this->ActionscandidatsPersonnesController->ajaxstruct($referent_id);
			$this->assertEqual($expected, $this->ActionscandidatsPersonnesController->viewVars['structs']);
			$this->assertEqual($expected, $this->ActionscandidatsPersonnesController->viewVars['referent']);
		}

		function testAjaxreffonct() {

			$referent_id = 1;
			$this->assertNull($this->ActionscandidatsPersonnesController->renderedAction);
			$this->assertNull($this->ActionscandidatsPersonnesController->renderedLayout);
			$this->ActionscandidatsPersonnesController->ajaxreffonct($referent_id);
			$this->assertEqual('ajaxreffonct', $this->ActionscandidatsPersonnesController->renderedAction);
			$this->assertEqual('ajax', $this->ActionscandidatsPersonnesController->renderedLayout);
		}

		public function testAdd() {
			$this->assertEqual(array(), $this->ActionscandidatsPersonnesController->viewVars);
			$this->ActionscandidatsPersonnesController->add();
			$this->assertNotEqual(array(), $this->ActionscandidatsPersonnesController->viewVars);
		}

		public function testEdit() {
			$this->assertEqual(array(), $this->ActionscandidatsPersonnesController->viewVars);
			$this->ActionscandidatsPersonnesController->edit();
			$this->assertNotEqual(array(), $this->ActionscandidatsPersonnesController->viewVars);
		}

		function test_add_edit() {
			$this->ActionscandidatsPersonnesController->params['form']['Cancel'] = '12345';
			$this->ActionscandidatsPersonnesController->data = '12345';
			$this->assertEqual(array(), $this->ActionscandidatsPersonnesController->viewVars);
			$this->ActionscandidatsPersonnesController->_add_edit();
			$this->assertEqual(array('controller' =>'actionscandidats_personnes','action' =>'index', '0' => null), $this->ActionscandidatsPersonnesController->redirectUrl);
			$this->assertNotEqual(array(), $this->ActionscandidatsPersonnesController->viewVars);
		}

		function testGedooo() {
			$id = null;
			var_dump($this->ActionscandidatsPersonnesController->Gedoo);
			$this->ActionscandidatsPersonnesController->gedooo($id);
			var_dump($this->ActionscandidatsPersonnesController->Gedoo);

		}
*/

	}

?>
