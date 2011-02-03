<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Cohortesindus');

	class TestCohortesindusController extends CohortesindusController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Cohortesindus';

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

	class CohortesindusControllerTest extends CakeAppControllerTestCase {

		function test__construct() {
			$this->assertNull($this->CohortesindusController->components['Prg']['actions']['1']);
			$this->CohortesindusController->__construct();
			$this->assertEqual('index', $this->CohortesindusController->components['Prg']['actions']['1']);
	        }

	        function testBeforeFilter() {
			$this->assertNull($this->CohortesindusController->redirectUrl);
			$this->assertNull($this->CohortesindusController->viewVars['sr']);
			$this->assertNull($this->CohortesindusController->viewVars['etatdosrsa']);
			$this->assertNull($this->CohortesindusController->viewVars['natpfcre']);
			$this->assertNull($this->CohortesindusController->viewVars['typeparte']);
			$this->CohortesindusController->beforeFilter();
			$this->assertEqual('/users/login', $this->CohortesindusController->redirectUrl);
			$this->assertNotNull($this->CohortesindusController->viewVars['sr']);
			$this->assertNotNull($this->CohortesindusController->viewVars['etatdosrsa']);
			$this->assertNotNull($this->CohortesindusController->viewVars['natpfcre']);
			$this->assertNotNull($this->CohortesindusController->viewVars['typeparte']);
	        }

	        function testIndex() {
			$this->assertNull($this->CohortesindusController->viewVars['cantons']);
			$this->assertNull($this->CohortesindusController->viewVars['mesCodesInsee']);
			$this->assertNull($this->CohortesindusController->viewVars['comparators']);
			$this->CohortesindusController->index();
			$this->assertNotNull($this->CohortesindusController->viewVars['cantons']);
			$this->assertNotNull($this->CohortesindusController->viewVars['mesCodesInsee']);
			$this->assertNotNull($this->CohortesindusController->viewVars['comparators']);
		}

	        function testExportcsv() {
			$this->assertNull($this->CohortesindusController->viewVars['indus']);
			$this->CohortesindusController->exportcsv();
			$this->assertNotNull($this->CohortesindusController->viewVars['indus']);
	        }

	}

?>
