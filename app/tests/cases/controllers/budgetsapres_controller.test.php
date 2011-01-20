<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Budgetsapres');

	class TestBudgetsapresController extends BudgetsapresController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Budgetsapres';

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

	class BudgetsapresControllerTest extends CakeAppControllerTestCase {

		public function testIndex() {
			$this->assertNull($this->BudgetsapresController->viewVars['budgetsapres']);
			$this->BudgetsapresController->index();
			$this->assertNotNull($this->BudgetsapresController->viewVars['budgetsapres']);
		}

		public function testAdd() {
			$this->assertNull($this->BudgetsapresController->renderedLayout);
			$this->assertNull($this->BudgetsapresController->renderedFile);
			$this->BudgetsapresController->add();
			$this->assertEqual('default', $this->BudgetsapresController->renderedLayout);
			$this->assertEqual('add_edit', $this->BudgetsapresController->renderedFile);
	        }

	        public function testEdit() {
			$this->assertNull($this->BudgetsapresController->renderedLayout);
			$this->assertNull($this->BudgetsapresController->renderedFile);
			$this->BudgetsapresController->edit();
			$this->assertEqual('default', $this->BudgetsapresController->renderedLayout);
			$this->assertEqual('add_edit', $this->BudgetsapresController->renderedFile);
	        }

	        function test_add_edit() {
			$id = 1;
			$this->assertNull($this->BudgetsapresController->renderedLayout);
			$this->assertNull($this->BudgetsapresController->renderedFile);
			$this->BudgetsapresController->_add_edit($id);
			$this->assertEqual('default', $this->BudgetsapresController->renderedLayout);
			$this->assertEqual('add_edit', $this->BudgetsapresController->renderedFile);
		}

	}

?>
