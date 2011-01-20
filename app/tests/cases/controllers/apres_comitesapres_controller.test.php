<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'ApresComitesapres');

	class TestApresComitesapresController extends ApresComitesapresController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='ApresComitesapres';

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

	class ApresComitesapresControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->assertNull($this->ApresComitesapresController->redirectUrl);
			$this->assertNull($this->ApresComitesapresController->viewVars['etatdosrsa']);
			$this->assertNull($this->ApresComitesapresController->viewVars['options']);
			$this->ApresComitesapresController->beforeFilter();
			$this->assertEqual('/users/login', $this->ApresComitesapresController->redirectUrl);
			$this->assertNotNull($this->ApresComitesapresController->viewVars['etatdosrsa']);
			$this->assertNotNull($this->ApresComitesapresController->viewVars['options']);
		}

	        public function testAdd() {
			$this->ApresComitesapresController->add();
		}

        	public function testEdit() {
			$this->ApresComitesapresController->edit();
		}

        	function test_add_edit(){
			$id = 1;
			$this->ApresComitesapresController->_add_edit($id);
		}

	}

?>
