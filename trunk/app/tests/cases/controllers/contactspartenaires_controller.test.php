<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );
	
	App::import('Controller', 'Contactspartenaires');

	class TestContactspartenairesController extends ContactspartenairesController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Contactspartenaires';

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

		public function add() {
			$this->action = 'add';
			parent::add();
		}

		public function edit() {
			$this->action = 'edit';
			parent::edit();
		}

		public function _add_edit() {
			$this->action = '_add_edit';
			parent::_add_edit();
		}
 
		public function delete($id) {
			$this->action = 'delete';
			parent::delete($id);
		}
	}

	class ContactspartenairesControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->ContactspartenairesController->beforeFilter();
			$this->assertEqual('/users/login', $this->ContactspartenairesController->redirectUrl);
			$this->assertNotNull($this->ContactspartenairesController->viewVars['qual']);
			$this->assertNotNull($this->ContactspartenairesController->viewVars['options']);
			$this->assertNotNull($this->ContactspartenairesController->viewVars['etatdosrsa']);
	        }
/*
	        public function testIndex() {
			$this->ContactspartenairesController->index();
			$this->assertNotNull($this->ContactspartenairesController->viewVars['contactspartenaires']);
	        }
*/
	        public function testAdd() {
			$this->ContactspartenairesController->add();
			$this->assertEqual('_add_edit', $this->ContactspartenairesController->renderedAction);
			$this->assertEqual('default', $this->ContactspartenairesController->renderedLayout);
			$this->assertEqual('add_edit', $this->ContactspartenairesController->renderedFile);
	        }

	        public function testEdit() {
			$this->ContactspartenairesController->edit();
			$this->assertEqual('_add_edit', $this->ContactspartenairesController->renderedAction);
			$this->assertEqual('default', $this->ContactspartenairesController->renderedLayout);
			$this->assertEqual('add_edit', $this->ContactspartenairesController->renderedFile);
	        }

	        function test_add_edit(){
			$this->ContactspartenairesController->_add_edit();
			$this->assertEqual('default', $this->ContactspartenairesController->renderedLayout);
			$this->assertEqual('add_edit', $this->ContactspartenairesController->renderedFile);
	        }

	        public function testDelete() {
			$id = '1';
			$this->ContactspartenairesController->delete($id);
			$this->assertEqual('/', $this->ContactspartenairesController->redirectUrl);
	        }

	        public function testView() {
			$id = '1';
			$this->ContactspartenairesController->view($id);
			$this->assertNotNull($this->ContactspartenairesController->viewVars['contactpartenaire']);
	        }

	}

?>
