<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Comitesapres');

	class TestComitesapresController extends ComitesapresController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Comitesapres';

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

	class ComitesapresControllerTest extends CakeAppControllerTestCase {

		function test_setOptions() {
			$this->ComitesapresController->_setOptions();
			$this->assertNotNull($this->ComitesapresController->viewVars['options']);
			$this->assertNotNull($this->ComitesapresController->viewVars['referent']);
	        }

	        function testIndex() {
			$this->ComitesapresController->index();
			$this->assertEqual('default', $this->ComitesapresController->renderedLayout);
			$this->assertEqual('index', $this->ComitesapresController->renderedFile);
			$this->assertEqual('Recherche de comités', $this->ComitesapresController->viewVars['pageTitle']);
	        }

	        function testListe() {
			$this->ComitesapresController->liste();
			$this->assertEqual('default', $this->ComitesapresController->renderedLayout);
			$this->assertEqual('liste', $this->ComitesapresController->renderedFile);
			$this->assertEqual('Liste des comités', $this->ComitesapresController->viewVars['pageTitle']);
	        }

	        function test_index() {
			$display = null;
			$this->ComitesapresController->_index($display);
	        }

        /** **************************************************************************************
        *   Affichage du Comité après sa création permettant ajout des APREs et des Participants
        *** *************************************************************************************/

	        function testView(){
			$comiteapre_id = 1;
			$this->ComitesapresController->view($comiteapre_id);
			$this->assertNotNull($this->ComitesapresController->viewVars['comiteapre']);
	        }

        /** **********************************************************************************************
        *   Affichage du rapport suite au Comité ( présence / absence des participants + décision APREs)
        *** **********************************************************************************************/

	        function testRapport(){
			$comiteapre_id = 1;
			$this->ComitesapresController->rapport($comiteapre_id);
			$this->assertNotNull($this->ComitesapresController->viewVars['comiteapre']);
			$this->assertNotNull($this->ComitesapresController->viewVars['participants']);
	        }

	        public function testAdd() {
			$this->ComitesapresController->add();
			$this->assertNotNull($this->ComitesapresController->viewVars['referent']);
			$this->assertNotNull($this->ComitesapresController->viewVars['options']);
			$this->assertEqual('default', $this->ComitesapresController->renderedLayout);
			$this->assertEqual('add_edit', $this->ComitesapresController->renderedFile);
	        }

	        public function testEdit() {
			$this->ComitesapresController->edit();
			$this->assertEqual('default', $this->ComitesapresController->renderedLayout);
			$this->assertEqual('add_edit', $this->ComitesapresController->renderedFile);
	        }

	        function test_add_edit() {
			$id = 1;
			$this->ComitesapresController->_add_edit($id);
			$this->assertNotNull($this->ComitesapresController->viewVars['referent']);
			$this->assertNotNull($this->ComitesapresController->viewVars['options']);
			$this->assertEqual('default', $this->ComitesapresController->renderedLayout);
			$this->assertEqual('add_edit', $this->ComitesapresController->renderedFile);
	        }
/*
	        function testExportcsv() {
			$this->ComitesapresController->exportcsv();
			$this->assertNotNull($this->ComitesapresController->viewVars['referent']);
			$this->assertNotNull($this->ComitesapresController->viewVars['options']);
			$this->assertNotNull($this->ComitesapresController->viewVars['comitesapres']);
	        }
*/
	}

?>
