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

	}

	class CohortescomitesapresControllerTest extends CakeAppControllerTestCase {

		function test__construct() {
			$this->CohortescomitesapresController->__construct();
		}
/*
		protected function test_setOptions() {
			$this->CohortescomitesapresController->_setOptions();
		}

		function testAviscomite() {
			$this->CohortescomitesapresController->aviscomite();
		}

		function testNotificationscomite() {
			$this->CohortescomitesapresController->notificationscomite();
		}

		function test_index() {
			$avisComite = null;
			$this->CohortescomitesapresController->_index($avisComite);
		}

		function testExportcsv() {
			$this->CohortescomitesapresController->exportcsv();
		}

		function testEditdecision() {
			$apre_id = 1;
			$this->CohortescomitesapresController->editdecision($apre_id);
		}

		function testNotificationscomitegedooo() {
			$apre_comiteapre_id = 1;
			$this->CohortescomitesapresController->notificationscomitegedooo($apre_comiteapre_id);
		}
*/
	}

?>
