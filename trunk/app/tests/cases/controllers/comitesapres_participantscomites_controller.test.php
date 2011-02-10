<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'ComitesapresParticipantscomites');

	class TestComitesapresParticipantscomitesController extends ComitesapresParticipantscomitesController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='ComitesapresParticipantscomites';

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

	class ComitesapresParticipantscomitesControllerTest extends CakeAppControllerTestCase {

		function test_setOptions() {
			$this->assertNull($this->ComitesapresParticipantscomitesController->viewVars['participants']);
			$this->assertNull($this->ComitesapresParticipantscomitesController->viewVars['options']);
			$this->ComitesapresParticipantscomitesController->_setOptions();
			$this->assertNotNull($this->ComitesapresParticipantscomitesController->viewVars['participants']);
			$this->assertNotNull($this->ComitesapresParticipantscomitesController->viewVars['options']);
	        }

	        public function testAdd() {
			$this->ComitesapresParticipantscomitesController->add();
	        }

	        public function testEdit() {
			$this->ComitesapresParticipantscomitesController->edit();
	        }

	        function test_add_edit() {
			$id = 1;
			$this->ComitesapresParticipantscomitesController->_add_edit($id);
		}

	        function testRapport() {
			$comiteapre_id = 1;
			$this->assertNull($this->ComitesapresParticipantscomitesController->viewVars['comiteparticipant']);
			$this->assertNull($this->ComitesapresParticipantscomitesController->viewVars['participants']);
			$this->assertNull($this->ComitesapresParticipantscomitesController->viewVars['options']);
			$this->ComitesapresParticipantscomitesController->rapport($comiteapre_id);
			$this->assertNotNull($this->ComitesapresParticipantscomitesController->viewVars['comiteparticipant']);
			$this->assertNotNull($this->ComitesapresParticipantscomitesController->viewVars['participants']);
			$this->assertNotNull($this->ComitesapresParticipantscomitesController->viewVars['options']);
		}

	}

?>
