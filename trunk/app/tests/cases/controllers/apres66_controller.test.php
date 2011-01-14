<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Apres66');

	class TestApres66Controller extends Apres66Controller {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Apres66';

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

	class Apres66ControllerTest extends CakeAppControllerTestCase {

		function testSetOptions() {
			$this->assertEqual($this->Apres66Controller->viewVars, array());
			$this->Apres66Controller->_setOptions();
			$this->assertNotNull($this->Apres66Controller->viewVars);
		}

		function testIndexparams(){
			$this->Apres66Controller->params['form']['Cancel'] = '12345';
			$this->Apres66Controller->indexparams();
			$this->assertEqual(array('controller' =>'parametrages','action' =>'index'),$this->Apres66Controller->redirectUrl);
		}

		public function testIndex() {
			$personne_id = 1;
			$this->Apres66Controller->index($personne_id);
			$this->assertEqual($personne_id, $this->Apres66Controller->viewVars['personne']['Personne']['id']);
			$this->assertEqual('/apres/index66', $this->Apres66Controller->renderedFile);
		}

		function testAjaxstruct() {
			$referent_id = 1;
			$this->assertNull($this->Apres66Controller->renderedLayout);
			$this->assertNull($this->Apres66Controller->renderedFile);

			$this->Apres66Controller->ajaxstruct($referent_id);
			$this->assertEqual('ajax', $this->Apres66Controller->renderedLayout);

			$this->assertEqual('/apres/ajaxstruct', $this->Apres66Controller->renderedFile);
		}

		function testAjaxref() {
			$referent_id = 1;
			$this->assertNull($this->Apres66Controller->renderedLayout);
			$this->assertNull($this->Apres66Controller->renderedFile);
			$this->assertNull($this->Apres66Controller->viewVars['referent']);

			$this->Apres66Controller->ajaxref($referent_id);

			$this->assertEqual('ajax', $this->Apres66Controller->renderedLayout);
			$this->assertEqual('/apres/ajaxref', $this->Apres66Controller->renderedFile);
			$this->assertNotNull($this->Apres66Controller->viewVars['referent']);
		}

	        function testAjaxpiece() {
			$typeaideapre66_id = 1;
			$aideapre66_id = 1;
			$this->assertNull($this->Apres66Controller->renderedLayout);
			$this->assertNull($this->Apres66Controller->renderedFile);
			$this->assertNull($this->Apres66Controller->viewVars['piecesadmin']);
			$this->assertNull($this->Apres66Controller->viewVars['piecescomptable']);
			$this->assertNull($this->Apres66Controller->viewVars['typeaideapre']);

			$this->Apres66Controller->ajaxpiece($typeaideapre66_id, $aideapre66_id);

			$this->assertEqual('ajax', $this->Apres66Controller->renderedLayout);
			$this->assertEqual('/apres/ajaxpiece', $this->Apres66Controller->renderedFile);
			$this->assertNotNull($this->Apres66Controller->viewVars['piecesadmin']);
			$this->assertNotNull($this->Apres66Controller->viewVars['piecescomptable']);
			$this->assertNotNull($this->Apres66Controller->viewVars['typeaideapre']);
		}

		public function testView() {
			$id = 1;
			$this->assertNull($this->Apres66Controller->viewVars['referents']);
			$this->assertNull($this->Apres66Controller->viewVars['apre']);
			$this->assertNull($this->Apres66Controller->viewVars['personne_id']);
			$this->Apres66Controller->view($id);
			$this->assertNotNull($this->Apres66Controller->viewVars['referents']);
			$this->assertNotNull($this->Apres66Controller->viewVars['apre']);
			$this->assertNotNull($this->Apres66Controller->viewVars['personne_id']);
	        }
/*
		public function testAdd() {
			//$this->Apres66Controller->add();
		}

	        public function testEdit() {

		}

	        function test_add_edit() {
			$id = null;
		}

	        function testNotifications() {
			$id = 1;
			$this->Apres66Controller->notifications($id);
		}
*/
	}

?>
