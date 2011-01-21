<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Cohortesci');

	class TestCohortesciController extends CohortesciController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Cohortesci';

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
		function _index($statutValidation) {
			return parent::_index($statutValidation);
		}

	}

	class CohortesciControllerTest extends CakeAppControllerTestCase {

		public function test__construct() {
			$this->CohortesciController->__construct();
			$this->assertNotNull($this->CohortesciController->components['Prg']);
		}

		public function testBeforeFilter() {
			$this->assertNull($this->CohortesciController->viewVars['oridemrsa']);
			$this->assertNull($this->CohortesciController->viewVars['typeserins']);
			$this->assertNull($this->CohortesciController->viewVars['']);
			$this->CohortesciController->beforeFilter();
			$this->assertNotNull($this->CohortesciController->viewVars['oridemrsa']);
			$this->assertNotNull($this->CohortesciController->viewVars['typeserins']);
			$this->assertNotNull($this->CohortesciController->viewVars['printed']);
		}

		public function testNouveaux() {
			$this->assertNull($this->CohortesciController->renderedLayout);
			$this->assertNull($this->CohortesciController->renderedFile);
			$this->assertNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNull($this->CohortesciController->viewVars['personne_suivi']);
			$this->assertNull($this->CohortesciController->viewVars['mesCodesInsee']);
			$this->assertNull($this->CohortesciController->viewVars['referents']);
			$this->assertNull($this->CohortesciController->viewVars['pageTitle']);
			$this->CohortesciController->nouveaux();
			$this->assertNotNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNotNull($this->CohortesciController->viewVars['personne_suivi']);
			$this->assertNotNull($this->CohortesciController->viewVars['mesCodesInsee']);
			$this->assertNotNull($this->CohortesciController->viewVars['referents']);
			$this->assertEqual('Contrats à valider', $this->CohortesciController->viewVars['pageTitle']);
		}

		public function testValides() {
			$this->assertNull($this->CohortesciController->renderedLayout);
			$this->assertNull($this->CohortesciController->renderedFile);
			$this->assertNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNull($this->CohortesciController->viewVars['personne_suivi']);
			$this->CohortesciController->nouveaux();
			$this->assertNotNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNotNull($this->CohortesciController->viewVars['personne_suivi']);
			$this->assertEqual('default', $this->CohortesciController->renderedLayout);
			$this->assertEqual('formulaire', $this->CohortesciController->renderedFile);
		}

		public function testEnattente() {
			$this->assertNull($this->CohortesciController->renderedLayout);
			$this->assertNull($this->CohortesciController->renderedFile);
			$this->assertNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNull($this->CohortesciController->viewVars['personne_suivi']);
			$this->CohortesciController->enattente();
			$this->assertNotNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNotNull($this->CohortesciController->viewVars['personne_suivi']);
			$this->assertEqual('default', $this->CohortesciController->renderedLayout);
			$this->assertEqual('formulaire', $this->CohortesciController->renderedFile);
		}

		public function test_selectReferents() {
			$structurereferente_id = 1;
			$this->assertTrue($this->CohortesciController->_selectReferents($structurereferente_id));
		}

		public function testAjaxreferent() {
			$this->assertNull($this->CohortesciController->renderedLayout);
			$this->CohortesciController->ajaxreferent();
			$this->assertEqual('ajax', $this->CohortesciController->renderedLayout);
		}

		function test_index() {
			$statutValidation = 1;
			$this->assertNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNull($this->CohortesciController->viewVars['personne_suivi']);
			$this->CohortesciController->_index($statutValidation);
			$this->assertNotNull($this->CohortesciController->viewVars['cantons']);
			$this->assertNotNull($this->CohortesciController->viewVars['personne_suivi']);
		}

		public function testExportcsv() {
			$this->assertNull($this->CohortesciController->viewVars['referents']);
			$this->assertNull($this->CohortesciController->viewVars['action']);
			$this->assertNull($this->CohortesciController->viewVars['contrats']);
			$this->CohortesciController->exportcsv();
			$this->assertNotNull($this->CohortesciController->viewVars['referents']);
			$this->assertNotNull($this->CohortesciController->viewVars['action']);
			$this->assertNotNull($this->CohortesciController->viewVars['contrats']);
		}

	}

?>
