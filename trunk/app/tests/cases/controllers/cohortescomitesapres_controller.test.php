<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Cohortescomitesapres');

	class TestCohortescomitesapresController extends CohortescomitesapresController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Cohortescomitesapres';

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
		function _setOptions() {
			return parent::_setOptions();
		}

	}

	class CohortescomitesapresControllerTest extends CakeAppControllerTestCase {

		function test__construct() {
			
			$this->CohortescomitesapresController->__construct();
			$this->assertNotNull($this->CohortescomitesapresController->components['Prg']);
		}

		function test_setOptions() {
			$this->CohortescomitesapresController->_setOptions();
			$referent = array(
				'2' => 'M Deufs John',
				'1' => 'M Némard Jean',
				'3003' => 'M troismille troisenfant',
			);
			$this->assertEqual($referent, $this->CohortescomitesapresController->viewVars['referent']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['options']);
		}

		function testAviscomite() {
			$this->assertNull($this->CohortescomitesapresController->renderedLayout);
			$this->assertNull($this->CohortescomitesapresController->renderedFile);
			$this->CohortescomitesapresController->aviscomite();
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['referent']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['options']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['pageTitle']);
			$this->assertEqual('default', $this->CohortescomitesapresController->renderedLayout);
			$this->assertEqual('formulaire', $this->CohortescomitesapresController->renderedFile);
		}

		function testNotificationscomite() {
			$this->assertNull($this->CohortescomitesapresController->renderedLayout);
			$this->assertNull($this->CohortescomitesapresController->renderedFile);
			$this->CohortescomitesapresController->notificationscomite();
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['referent']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['options']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['pageTitle']);
			$this->assertEqual('default', $this->CohortescomitesapresController->renderedLayout);
			$this->assertEqual('visualisation', $this->CohortescomitesapresController->renderedFile);
		}

		function test_index() {
			$avisComite = 1;
			$this->CohortescomitesapresController->_index($avisComite);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['referent']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['options']);
		}

		function testExportcsv() {
//			$this->assertNull($this->CohortescomitesapresController->viewVars['referent']);
//			$this->assertNull($this->CohortescomitesapresController->viewVars['options']);
			$this->CohortescomitesapresController->exportcsv();
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['referent']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['options']);
		}

		function testEditdecision() {
			$apre_id = 1;
//			$this->assertNull($this->CohortescomitesapresController->viewVars['referent']);
//			$this->assertNull($this->CohortescomitesapresController->viewVars['options']);
//			$this->assertNull($this->CohortescomitesapresController->viewVars['aprecomiteapre']);
//			$this->assertNull($this->CohortescomitesapresController->viewVars['apre']);
			$this->CohortescomitesapresController->editdecision($apre_id);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['referent']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['options']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['aprecomiteapre']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['apre']);
		}

		function testNotificationscomitegedooo() {
			$apre_comiteapre_id = 1;
//			$this->assertNull($this->CohortescomitesapresController->viewVars['options']);
//			$this->assertNull($this->CohortescomitesapresController->viewVars['referent']);
			$this->CohortescomitesapresController->notificationscomitegedooo($apre_comiteapre_id);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['options']);
			$this->assertNotNull($this->CohortescomitesapresController->viewVars['referent']);
		}

	}

?>
