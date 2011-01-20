<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Cohortes');

	class TestCohortesController extends CohortesController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Cohortes';

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

	class CohortesControllerTest extends CakeAppControllerTestCase {

		public function testBeforeFilter() {
			$this->assertNull($this->CohortesController->redirectUrl);
			$this->assertNull($this->CohortesController->viewVars['etatdosrsa']);
			$this->CohortesController->beforeFilter();
			$this->assertNotNull($this->CohortesController->viewVars['etatdosrsa']);
			$this->assertEqual('/users/login', $this->CohortesController->redirectUrl);
		}
/*
		public function test__construct() {
			$this->CohortesController->__construct();
		}

		public function testNouvelles() {

		}

		public function testOrientees() {
			
		}

		public function testEnattente() {
			
		}

		protected function test_index() {
			$statutOrientation = 1;
		}

		public function testexportcsv(){
			
		}

		protected function test_get() {
			$personne_id = 1;
		}

		public function testcohortegedooo() {
			$personne_id = 1;
		}
*/
	}

?>
