<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Cantons');

	class TestCantonsController extends CantonsController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Cantons';

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
		function _add_edit($id) {
			return parent::_add_edit($id);
		}

	}

	class CantonsControllerTest extends CakeAppControllerTestCase {

		public function testBeforeFilter() {
			$this->CantonsController->beforeFilter();
			$this->assertNotNull($this->CantonsController->viewVars['typevoie']);
		}
/*
		public function testIndex() {
			$this->CantonsController->params['forms']['Cancel'] = '12345';
			$this->CantonsController->index();
			$this->assertNotNull($this->CantonsController->viewVars['cantons']);
			//$this->assertEqual(array('controller' =>'parametrages','action' =>'index'),$this->CantonsController->redirectUrl);
		}

		public function testAdd() {
			$this->CantonsController->add();
			$this->assertEqual('default', $this->CantonsController->renderedLayout);
			$this->assertEqual('add_edit', $this->CantonsController->renderedFile);
			$this->assertNotNull($this->CantonsController->viewVars['zonesgeographiques']);
			$this->assertNotNull($this->CantonsController->viewVars['typesvoies']);
	        }

	        public function testEdit() {
			$this->CantonsController->edit();
			$this->assertEqual('default', $this->CantonsController->renderedLayout);
			$this->assertEqual('add_edit', $this->CantonsController->renderedFile);
	        }

	        function test_add_edit() {
			$id = 1;
			$this->CantonsController->_add_edit($id);
			$this->assertEqual('default', $this->CantonsController->renderedLayout);
			$this->assertEqual('add_edit', $this->CantonsController->renderedFile);
			$this->assertNotNull($this->CantonsController->viewVars['zonesgeographiques']);
			$this->assertNotNull($this->CantonsController->viewVars['typesvoies']);
			
		}
*/
	        public function testDelete() {
			$id = 1;
			$this->CantonsController->delete($id);
			$this->assertEqual(array('action' =>'index'),$this->CantonsController->redirectUrl);
		}

	}

?>
