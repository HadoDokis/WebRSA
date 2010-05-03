<?php

	require_once( dirname( __FILE__ ).'/../cake_app_test_case.php' );
	require_once( dirname( __FILE__ ).'/../../../app_controller.php' );

	App::import('Component','Default');

	class TestDefaultComponent extends DefaultComponent {
		public $autoRender = false;
		public $redirectUrl;
		public $redirectStatus;
		public $renderedAction;
		public $renderedLayout;
		public $renderedFile;
		public $stopped;
		public $name='Default';
	
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
	}

	class ItemsController extends AppController {
		public $name = 'Items';
		public $uses = array( 'Item' );
		public $components = array( 'TestDefault' );
	}

	class DefaultTest extends CakeAppTestCase {
		public function startTest() {
			ClassRegistry::config( array( 'ds' => 'test_suite' ) );
			$this->Items =& new ItemsController();
			$this->Items->constructClasses();
			$this->Items->Component->initialize( $this->Items );
		}

		public function tearDown() {
			unset( $this->Items );
 			ClassRegistry::flush();
		}

		public function testIndex() {
			$this->Items->params = array( 'url' => array( 'url' => 'items/' ), 'controller' => 'items', 'action' => 'index' );
			$this->Items->Default->index( array( 'Item' => array( 'conditions' => array( 'Item.id <' => 2 ) ) ) );
			$expected = array(
				"0" => array(
					"Item" => array(
							"id" => 1,
							"firstname" => "Firstname n°1",
							"lastname" => "Lastname n°1",
							"name_a" => "name_a",
							"name_b" => "name_b",
							"version_a" => 1,
							"version_n" => 1,
							"description_a" => "description_a",
							"description_b" => "description_b",
							"modifiable_a" => 1,
							"modifiable_b" => 1,
							"date_a" => "2010-03-17",
							"date_b" => "2010-03-17",
							"tel" => "0101010101",
							"fax" => "0101010101",
							"category_id" => 12,
							"foo" => "f",
							"bar" => "",
							"montant" => 666.66 
					)
				)
			);
			$this->assertEqual($expected, $this->Items->viewVars['items']);
		}

		public function testView() {
			$this->Items->params = array( 'url' => array( 'url' => 'items/view/1' ), 'controller' => 'items', 'action' => 'view', 'pass' => array( 1 ) );
			$this->Items->Default->view( 1 );
			$expected = array(
				"Item" => array(
						"id" => 1,
						"firstname" => "Firstname n°1",
						"lastname" => "Lastname n°1",
						"name_a" => "name_a",
						"name_b" => "name_b",
						"version_a" => 1,
						"version_n" => 1,
						"description_a" => "description_a",
						"description_b" => "description_b",
						"modifiable_a" => 1,
						"modifiable_b" => 1,
						"date_a" => "2010-03-17",
						"date_b" => "2010-03-17",
						"tel" => "0101010101",
						"fax" => "0101010101",
						"category_id" => 12,
						"foo" => "f",
						"bar" => "",
						"montant" => 666.66
				)
			);
			$this->assertEqual($expected, $this->Items->viewVars['item']);
		}

	}
?>