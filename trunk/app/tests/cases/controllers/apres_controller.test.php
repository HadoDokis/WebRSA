<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Apres');

	class TestApresController extends ApresController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Apres';

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

	}

	class ApresControllerTest extends CakeAppControllerTestCase {

		function testSetOptions() {
			$this->assertEqual($this->ApresController->viewVars, array());
			$this->ApresController->_setOptions();
			$this->assertNotNull($this->ApresController->viewVars);
		}

		function testIndexparams(){
			$this->ApresController->params['form']['Cancel'] = '12345';
			$this->ApresController->indexparams();
			$this->assertEqual(array('controller' =>'parametrages','action' =>'index'),$this->ApresController->redirectUrl);
		}

		function testIndex() {
			$personne_id = 1;
			$this->ApresController->index($personne_id);
			$this->assertNotNull($this->ApresController->viewVars['personne']);
			$this->assertNotNull($this->ApresController->viewVars['referents']);
			$this->assertNotNull($this->ApresController->viewVars['apres']);
		}

		function testAjaxstruct() { // FIXME
			$structurereferente_id = 1;
			$this->ApresController->ajaxstruct($structurereferente_id);
			$this->assertNotNull($this->ApresController->viewVars['struct']);
			$this->assertEqual('ajaxstruct', $this->ApresController->renderedAction);
			$this->assertEqual('ajax', $this->ApresController->renderedLayout);
		}

		function testAjaxref() { // FIXME
			$referent_id = 1;
			$this->ApresController->ajaxref($referent_id);
			$this->assertNotNull($this->ApresController->viewVars['referent']);
			$this->assertEqual('ajaxref', $this->ApresController->renderedAction);
			$this->assertEqual('ajax', $this->ApresController->renderedLayout);
		}

		function testAjaxtiersprestaformqualif() { // FIXME
			$tiersprestataireapre_id = 1;
			$this->ApresController->ajaxtiersprestaformqualif($tiersprestataireapre_id);
			$this->assertNotNull($this->ApresController->viewVars['tiersprestataireapre']);
			$this->assertEqual('ajaxtierspresta', $this->ApresController->renderedAction);
			$this->assertEqual('ajax', $this->ApresController->renderedLayout);
		}

		function testAjaxtiersprestaformpermfimo() { // FIXME
			$tiersprestataireapre_id = 1;
			$this->ApresController->ajaxtiersprestaformpermfimo($tiersprestataireapre_id);
			$this->assertNotNull($this->ApresController->viewVars['tiersprestataireapre']);
			$this->assertEqual('ajaxtierspresta', $this->ApresController->renderedAction);
			$this->assertEqual('ajax', $this->ApresController->renderedLayout);
		}

		function testAjaxtiersprestaactprof() { // FIXME
			$tiersprestataireapre_id = 1;
			$this->ApresController->ajaxtiersprestaactprof($tiersprestataireapre_id);
			$this->assertNotNull($this->ApresController->viewVars['tiersprestataireapre']);
			$this->assertEqual('ajaxtierspresta', $this->ApresController->renderedAction);
			$this->assertEqual('ajax', $this->ApresController->renderedLayout);
		}

		function testAjaxtiersprestapermisb() { // FIXME
			$tiersprestataireapre_id = 1;
			$this->ApresController->ajaxtiersprestapermisb($tiersprestataireapre_id);
			$this->assertNotNull($this->ApresController->viewVars['tiersprestataireapre']);
			$this->assertEqual('ajaxtierspresta', $this->ApresController->renderedAction);
			$this->assertEqual('ajax', $this->ApresController->renderedLayout);
		}

		function testView(){
			$apre_id = 1;
			$this->ApresController->view($apre_id);
			$this->assertNotNull($this->ApresController->viewVars['aprecomiteapre']);
			$this->assertNotNull($this->ApresController->viewVars['referents']);
			$this->assertNotNull($this->ApresController->viewVars['apre']);
		}
/*
		public function testAdd() {
			$this->ApresController->add();
		}

		public function testEdit() {
			$this->ApresController->edit();
		}

		function test_add_edit() {
			$id = 1;
			$this->ApresController->_add_edit($id);
		}
*/
	}

?>
