<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Aidesapres66');

	class TestAidesapres66Controller extends Aidesapres66Controller {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Aidesapres66';

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

	class Aidesapres66ControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->Aidesapres66Controller->beforeFilter();

			$this->assertNotNull($this->Aidesapres66Controller->viewVars['etatdosrsa']);
			$this->assertNotNull($this->Aidesapres66Controller->viewVars['options']);
			$this->assertNotNull($this->Aidesapres66Controller->viewVars['pieceliste']);
			$this->assertNotNull($this->Aidesapres66Controller->redirectUrl);
			$this->assertEqual('/users/login', $this->Aidesapres66Controller->redirectUrl);
		}
/*
	        public function testIndex() {
			$this->Aidesapres66Controller->index();

			$this->assertNotNull($this->Aidesapres66Controller->params['paging']);
	        }
*/
	        public function testAdd() {
			//$this->Aidesapres66Controller->add();
	        }

	        public function testEdit() {

	        }

	        function test_add_edit(){
        
	        }

	        public function testDelete() {
			$id = 1;
			$this->Aidesapres66Controller->delete($id);
			$this->assertEqual('/', $this->Aidesapres66Controller->redirectUrl);
	        }

	        public function testView() {
			$id = 1;
			$this->Aidesapres66Controller->view($id);
			$this->assertNotNull($this->Aidesapres66Controller->viewVars['aideapre66']);
	        }

	}

?>
