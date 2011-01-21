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

		// Attention on surcharge la visibilite du parent
		function _index($statutOrientation) {
			return parent::_index($statutOrientation);
		}

		// Attention on surcharge la visibilite du parent
		function _get($personne_id) {
			return parent::_get($personne_id);
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

		public function test__construct() {
			$this->assertFalse(in_array("Jetons", $this->CohortesController->components));
			$this->CohortesController->__construct();
			$this->assertTrue(in_array("Jetons", $this->CohortesController->components));

		}

		public function testNouvelles() {
			$this->assertNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNull($this->CohortesController->viewVars['printed']);
			$this->CohortesController->nouvelles();
			$this->assertNotNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNotNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNotNull($this->CohortesController->viewVars['printed']);
		}

		public function testOrientees() {
			$this->assertNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNull($this->CohortesController->viewVars['printed']);
			$this->CohortesController->orientees();
			$this->assertNotNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNotNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNotNull($this->CohortesController->viewVars['printed']);
		}

		public function testEnattente() {
			$this->assertNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNull($this->CohortesController->viewVars['printed']);
			$this->CohortesController->enattente();
			$this->assertNotNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNotNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNotNull($this->CohortesController->viewVars['printed']);
		}

		function test_index() {
			$statutOrientation = 1;
			$this->assertNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNull($this->CohortesController->viewVars['printed']);
			$this->assertNull($this->CohortesController->viewVars['cantons']);
			$this->CohortesController->_index($statutOrientation);
			$this->assertNotNull($this->CohortesController->viewVars['cantons']);
			$this->assertNotNull($this->CohortesController->viewVars['oridemrsa']);
			$this->assertNotNull($this->CohortesController->viewVars['typeserins']);
			$this->assertNotNull($this->CohortesController->viewVars['printed']);
		}

		public function testExportcsv() {
			$this->assertNull($this->CohortesController->viewVars['cohortes']);
			$this->CohortesController->exportcsv();
			$this->assertNotNull($this->CohortesController->viewVars['cohortes']);
		}

		function test_get() {
			$personne_id = 1;
			$this->assertTrue($this->CohortesController->_get($personne_id));
		}

		public function testCohortegedooo() {
			$personne_id = 1;
			$this->assertTrue($this->CohortesController->cohortegedooo($personne_id));
		}

	}

?>
