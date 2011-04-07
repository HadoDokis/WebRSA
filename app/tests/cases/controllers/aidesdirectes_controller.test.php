<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Aidesdirectes');

	class TestAidesdirectesController extends AidesdirectesController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Aidesdirectes';

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

	class AidesdirectesControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->AidesdirectesController->beforeFilter();

			$this->assertNotNull($this->AidesdirectesController->viewVars['etatdosrsa']);
			$this->assertNotNull($this->AidesdirectesController->viewVars['actions']);
			$this->assertNotNull($this->AidesdirectesController->viewVars['typo_aide']);
		}

		function testAdd() {
			$contratinsertion_id = 1;
			$this->AidesdirectesController->add($contratinsertion_id);

			$this->assertEqual('default', $this->AidesdirectesController->renderedLayout);
			$this->assertEqual('add_edit', $this->AidesdirectesController->renderedFile);
			$this->assertNotNull($this->AidesdirectesController->viewVars['personne_id']);
		}

		function testEdit(){
			$aidedirecte_id = 1;
			$this->AidesdirectesController->edit($aidedirecte_id);

			$this->assertEqual('default', $this->AidesdirectesController->renderedLayout);
			$this->assertEqual('add_edit', $this->AidesdirectesController->renderedFile);
			$this->assertNotNull($this->AidesdirectesController->viewVars['personne_id']);
		}
	}
?>
