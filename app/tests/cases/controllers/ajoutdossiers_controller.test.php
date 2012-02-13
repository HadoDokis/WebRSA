<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Ajoutdossiers');

	class TestAjoutdossiersController extends AjoutdossiersController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Ajoutdossiers';

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

	class AjoutdossiersControllerTest extends CakeAppControllerTestCase {

		function testRand_nir() {
			$this->assertNotNull(rand_nir());
		}

		function testHasConjoint() {
			$data = array();
			$this->assertNotNull(hasConjoint($data));
		}

	        function testBeforeFilter() {
			$this->AjoutdossiersController->beforeFilter();
			$this->assertNotNull($this->AjoutdossiersController->Wizard->steps['0']);
			$this->assertEqual('allocataire', $this->AjoutdossiersController->Wizard->steps['0']);
		}

		function testConfirm() {
			$this->assertNull($this->AjoutdossiersController->confirm());
		}
/*
		function testWizard() {
			$step = array('allocataire', 'conjoint', 'adresse', 'ressourcesallocataire', 'ressourcesconjoint', 'dossier');
			$this->AjoutdossiersController->wizard($step);
			$this->assertNotNull($this->AjoutdossiersController->viewVars['typeservice']);
		}
*/
		function test_processAllocataire() {
			$this->AjoutdossiersController->_processAllocataire();
		}
/*
		function test_processConjoint() {
			$pers_id = '4';
			$this->Personne = ClassRegistry::init('Personne');
			$cjt = $this->Personne->find(
				'first', array(
					'conditions' => array(
						'Personne.id' => $pers_id,
					)
				)
			);
			$this->AjoutdossiersController->data = $cjt;
			$this->AjoutdossiersController->_processConjoint();
		}
*/
		function test_processAdresse() {
			$this->AjoutdossiersController->_processAdresse();
		}
/*
	        function test_processRessourcesallocataire() {
			$this->AjoutdossiersController->_processRessourcesallocataire();
		}

		function test_processRessourcesconjoint() {
			$this->AjoutdossiersController->_processRessourcesconjoint();
		}

		function test_processDossier() {
			$this->AjoutdossiersController->_processDossier();
		}

	        function test_afterComplete() {
			$this->AjoutdossiersController->_afterComplete();
		}
*/
	}

?>
