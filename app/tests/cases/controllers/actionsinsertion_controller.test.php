<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Actionsinsertion');

	class TestActionsinsertionController extends ActionsinsertionController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Actionsinsertion';

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

	class ActionsinsertionControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->assertNull($this->ActionsinsertionController->viewVars['etatdosrsa']);
			$this->assertNull($this->ActionsinsertionController->viewVars['lib_action']);
			$this->assertNull($this->ActionsinsertionController->viewVars['actions']);
			$this->assertNull($this->ActionsinsertionController->viewVars['typo_aide']);			
			
			$this->ActionsinsertionController->beforeFilter();
			
			$this->assertNotNull($this->ActionsinsertionController->viewVars['etatdosrsa']);
			$this->assertNotNull($this->ActionsinsertionController->viewVars['lib_action']);
			$this->assertNotNull($this->ActionsinsertionController->viewVars['actions']);
			$this->assertNotNull($this->ActionsinsertionController->viewVars['typo_aide']);
        	}

	        function testIndex() {
			$contratinsertion_id = 1;
			
			$this->assertNull($this->ActionsinsertionController->viewVars['actions']);
			$this->assertNull($this->ActionsinsertionController->viewVars['actionsinsertion']);
			$this->assertNull($this->ActionsinsertionController->viewVars['contratinsertion_id']);
			$this->assertNull($this->ActionsinsertionController->viewVars['personne_id']);

			$this->ActionsinsertionController->index($contratinsertion_id);

			$this->assertNotNull($this->ActionsinsertionController->viewVars['actions']);
			$this->assertNotNull($this->ActionsinsertionController->viewVars['actionsinsertion']);
			$this->assertNotNull($this->ActionsinsertionController->viewVars['contratinsertion_id']);
			$this->assertNotNull($debug($this->viewVars);this->ActionsinsertionController->viewVars['personne_id']);

			$this->assertEqual($contratinsertion_id, $this->ActionsinsertionController->viewVars['contratinsertion_id']);
			$this->assertEqual(1, $this->ActionsinsertionController->viewVars['personne_id']);
	        }

	        function testEdit() {
			$contratinsertion_id = 1;
			$this->assertEqual(array(), $this->ActionsinsertionController->data);

			$this->ActionsinsertionController->edit($contratinsertion_id);

			$this->assertNotNull($this->ActionsinsertionController->data);
			$this->assertNotEqual(array(), $this->ActionsinsertionController->data);
	        }

	}

?>
