<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Dossiers');

	class TestDossiersController extends DossiersController {
	
		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Dossiers';
	
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
			return $condition;
		}
	}

	class DossiersControllerTest extends CakeAppControllerTestCase {

		function testView() {
			//$this->DossiersController->view("abc");
			//$this->assertEqual("invalidParameter",$this->DossiersController->error);

			//$this->assertTrue($this->DossiersController->viewVars['dossier']['Dossier']);
			//$this->assertEqual(3, $this->DossiersController->viewVars['dossier']['Dossier']['id']);
			//$this->assertEqual(33333333333, $this->DossiersController->viewVars['dossier']['Dossier']['numdemrsa']);
		}

		function testBeforeFilter() {
			$result=$this->DossiersController->beforeFilter();
			//debug($this->DossiersController->viewVars);
		}
	}

?>
