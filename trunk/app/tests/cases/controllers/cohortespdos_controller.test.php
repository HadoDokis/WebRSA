<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Cohortespdos');

	class TestCohortespdosController extends CohortespdosController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Cohortespdos';

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

	class CohortespdosControllerTest extends CakeAppControllerTestCase {

		function test__construct() {
			$this->assertNull($this->CohortespdosController->components['Prg']['actions']['1']);
			$this->CohortespdosController->__construct();
			$this->assertEqual('valide', $this->CohortespdosController->components['Prg']['actions']['1']);
	        }

	        function testBeforeFilter(){
			$this->assertNull($this->CohortespdosController->redirectUrl);
			$this->assertNull($this->CohortespdosController->viewVars['decisionpdo']);
			$this->assertNull($this->CohortespdosController->viewVars['pieecpres']);
			$this->assertNull($this->CohortespdosController->viewVars['commission']);
			$this->assertNull($this->CohortespdosController->viewVars['etatdosrsa']);
			$this->assertNull($this->CohortespdosController->viewVars['decisionpdo']);
			$this->assertNull($this->CohortespdosController->viewVars['pieecpres']);
			$this->assertNull($this->CohortespdosController->viewVars['commission']);
			$this->CohortespdosController->beforeFilter();
			$this->assertEqual('/users/login', $this->CohortespdosController->redirectUrl);
			$this->assertNotNull($this->CohortespdosController->viewVars['etatdosrsa']);
			$this->assertNotNull($this->CohortespdosController->viewVars['decisionpdo']);
			$this->assertNotNull($this->CohortespdosController->viewVars['pieecpres']);
			$this->assertNotNull($this->CohortespdosController->viewVars['commission']);
	        }

	        function testAvisdemande() {
			$this->assertNull($this->CohortespdosController->renderedLayout);
			$this->assertNull($this->CohortespdosController->renderedFile);
			$this->assertNull($this->CohortespdosController->viewVars['cantons']);
			$this->assertNull($this->CohortespdosController->viewVars['mesCodesInsee']);
			$this->assertNull($this->CohortespdosController->viewVars['pageTitle']);
			$this->CohortespdosController->avisdemande();
			$this->assertEqual('default', $this->CohortespdosController->renderedLayout);
			$this->assertEqual('formulaire', $this->CohortespdosController->renderedFile);
			$this->assertNotNull($this->CohortespdosController->viewVars['cantons']);
			$this->assertNotNull($this->CohortespdosController->viewVars['mesCodesInsee']);
			$this->assertNotNull($this->CohortespdosController->viewVars['pageTitle']);
	        }

	        function testValide() {
			$this->assertNull($this->CohortespdosController->renderedLayout);
			$this->assertNull($this->CohortespdosController->renderedFile);
			$this->assertNull($this->CohortespdosController->viewVars['cantons']);
			$this->assertNull($this->CohortespdosController->viewVars['mesCodesInsee']);
			$this->assertNull($this->CohortespdosController->viewVars['pageTitle']);
			$this->CohortespdosController->valide();
			$this->assertEqual('default', $this->CohortespdosController->renderedLayout);
			$this->assertEqual('visualisation', $this->CohortespdosController->renderedFile);
			$this->assertNotNull($this->CohortespdosController->viewVars['cantons']);
			$this->assertNotNull($this->CohortespdosController->viewVars['mesCodesInsee']);
			$this->assertNotNull($this->CohortespdosController->viewVars['pageTitle']);
	        }

	        function test_index() {
			$statutValidationAvis = null;
			$this->assertNull($this->CohortespdosController->viewVars['cantons']);
			$this->assertNull($this->CohortespdosController->viewVars['mesCodesInsee']);
			$this->CohortespdosController->_index($statutValidationAvis);
			$this->assertNotNull($this->CohortespdosController->viewVars['cantons']);
			$this->assertNotNull($this->CohortespdosController->viewVars['mesCodesInsee']);
	        }

	        function testExportcsv() {
			$this->CohortespdosController->exportcsv();
	        }

	}

?>
