<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Adressesfoyers');

	class TestAdressesfoyersController extends AdressesfoyersController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Adressesfoyers';

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

	}

	class AdressesfoyersControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {			
			$this->assertNull($this->AdressesfoyersController->viewVars['etatdosrsa']);
			$this->assertNull($this->AdressesfoyersController->viewVars['pays']);
			$this->assertNull($this->AdressesfoyersController->viewVars['rgadr']);
			$this->assertNull($this->AdressesfoyersController->viewVars['typeadr']);
			$this->assertNull($this->AdressesfoyersController->viewVars['typevoie']);

			$this->AdressesfoyersController->beforeFilter();

			$this->assertNotNull($this->AdressesfoyersController->viewVars['etatdosrsa']);
			$this->assertNotNull($this->AdressesfoyersController->viewVars['pays']);
			$this->assertNotNull($this->AdressesfoyersController->viewVars['rgadr']);
			$this->assertNotNull($this->AdressesfoyersController->viewVars['typeadr']);
			$this->assertNotNull($this->AdressesfoyersController->viewVars['typevoie']);
		}

		function testIndex() {
			$foyer_id = 1;

			$this->assertNull($this->AdressesfoyersController->viewVars['foyer_id']);
			$this->assertNull($this->AdressesfoyersController->viewVars['adresses']);

			$this->AdressesfoyersController->index($foyer_id);

			$this->assertNotNull($this->AdressesfoyersController->viewVars['foyer_id']);
			$this->assertNotNull($this->AdressesfoyersController->viewVars['adresses']);
		}

		function testView() {
			$id = 1;

			$this->assertNull($this->AdressesfoyersController->viewVars['adresse']);

			$this->AdressesfoyersController->view($id);

			$this->assertNotNull($this->AdressesfoyersController->viewVars['adresse']);
		}

		function testEdit() {
			$id = 1;
			$this->assertNull($this->AdressesfoyersController->renderedFile);
			$this->assertNull($this->AdressesfoyersController->renderedLayout);

			$this->AdressesfoyersController->edit($id);

			$this->assertEqual('add_edit', $this->AdressesfoyersController->renderedFile);
			$this->assertEqual('default', $this->AdressesfoyersController->renderedLayout);
		}

		function testAdd() {
			$foyer_id = 1;
			
			$this->assertNull($this->AdressesfoyersController->renderedFile);
			$this->assertNull($this->AdressesfoyersController->renderedLayout);
			$this->assertNull($this->AdressesfoyersController->viewVars['foyer_id']);

			$this->AdressesfoyersController->add($foyer_id);

			$this->assertEqual('add_edit', $this->AdressesfoyersController->renderedFile);
			$this->assertEqual('default', $this->AdressesfoyersController->renderedLayout);
			$this->assertNotNull($this->AdressesfoyersController->viewVars['foyer_id']);
			$this->assertEqual($foyer_id, $this->AdressesfoyersController->viewVars['foyer_id']);
		}

	}

?>
