<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'ActionscandidatsPartenaires');

	class TestActionscandidatsPartenairesController extends ActionscandidatsPartenairesController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='ActionscandidatsPartenaires';

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

	class ActionscandidatsPartenairesControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->ActionscandidatsPartenairesController->beforeFilter();
			$expected = array(
				'ActioncandidatPartenaire' => array(
					'actioncandidat_id' => array(
						'1' => 'intitulé',
                                	),
					'partenaire_id' => array(
						'1' => 'libstruct?',
	                                ),
                        	),
                	);
			$this->assertEqual($this->ActionscandidatsPartenairesController->viewVars['options'], $expected);
	        }

		function testIndex()  {
			$this->ActionscandidatsPartenairesController->index();
			$this->assertNotNull($this->ActionscandidatsPartenairesController->params['paging']);
	        }
/*
		function testAdd() {
			$this->ActionscandidatsPartenairesController->add();	            
	        }
	
	        function testEdit() {
			$this->ActionscandidatsPartenairesController->edit();
	        }
	
	        function test_add_edit() {
			$this->ActionscandidatsPartenairesController->_add_edit();
	        }
*/
	        function testDelete() {
			$id = '1';
			$this->assertNull($this->ActionscandidatsPartenairesController->redirectUrl);
			$this->ActionscandidatsPartenairesController->delete($id);
			$this->assertEqual('/' ,$this->ActionscandidatsPartenairesController->redirectUrl);
			
	        }
	
	        function testView() {
			$id = '1';
			$this->ActionscandidatsPartenairesController->view();
			$this->assertNotNull($this->ActionscandidatsPartenairesController->viewVars['actionscandidatspartenaire']);
	        }
	

	}

?>
