<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Actionscandidats');

	class TestActionscandidatsController extends ActionscandidatsController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Actionscandidats';

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

	class ActionscandidatsControllerTest extends CakeAppControllerTestCase {

	        function testBeforeFilter() {
			$result = $this->ActionscandidatsController->beforeFilter();
			var_dump($result);	
	        }
/*	
		function testIndex()  {
			$result = $this->ActionscandidatsController->index();
	        }
	
		function testAdd() {
			$this->ActionscandidatsController->add();	            
	        }
	
	        function testEdit() {
			$this->ActionscandidatsController->edit();
	        }
	
	        function test_add_edit() {
			$this->ActionscandidatsController->_add_edit();
	        }
	
	        function testDelete() {
			$id = '1';
			$this->ActionscandidatsController->delete();
	        }
	
	        function testView() {
			$id = '1';
			$this->ActionscandidatsController->view();
	        }
*/	
	}

?>
