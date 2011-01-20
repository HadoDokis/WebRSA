<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Avispcgdroitsrsa');

	class TestAvispcgdroitsrsaController extends AvispcgdroitsrsaController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Avispcgdroitrsa';

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

	class AvispcgdroitsrsaControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->assertNull($this->AvispcgdroitsrsaController->viewVars['avisdestpairsa']);
			$this->assertNull($this->AvispcgdroitsrsaController->viewVars['typeperstie']);
			$this->assertNull($this->AvispcgdroitsrsaController->viewVars['aviscondadmrsa']);
			$this->AvispcgdroitsrsaController->beforeFilter();
			$this->assertNotNull($this->AvispcgdroitsrsaController->viewVars['avisdestpairsa']);
			$this->assertNotNull($this->AvispcgdroitsrsaController->viewVars['typeperstie']);
			$this->assertNotNull($this->AvispcgdroitsrsaController->viewVars['aviscondadmrsa']);
	        }


	        function testIndex() {
			$dossier_id = 1;
			$this->assertNull($this->AvispcgdroitsrsaController->viewVars['dossier_id']);
			$this->assertNull($this->AvispcgdroitsrsaController->viewVars['avispcgdroitrsa']);
			$this->AvispcgdroitsrsaController->index($dossier_id);
			$this->assertNotNull($this->AvispcgdroitsrsaController->viewVars['dossier_id']);
			$this->assertNotNull($this->AvispcgdroitsrsaController->viewVars['avispcgdroitrsa']);
	        }


	        function testView() {
			$avispcgdroitrsa_id = 1;
			$this->assertNull($this->AvispcgdroitsrsaController->viewVars['dossier_id']);
			$this->assertNull($this->AvispcgdroitsrsaController->viewVars['avispcgdroitrsa']);
			$this->AvispcgdroitsrsaController->view($avispcgdroitrsa_id);
			$this->assertNotNull($this->AvispcgdroitsrsaController->viewVars['dossier_id']);
			$this->assertNotNull($this->AvispcgdroitsrsaController->viewVars['avispcgdroitrsa']);
	        }

	}

?>
