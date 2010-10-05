<?php
	if( !defined( 'CAKEPHP_UNIT_TEST_EXECUTION' ) ) {
		define( 'CAKEPHP_UNIT_TEST_EXECUTION', 1 );
	}
	
	require_once( 'cake_app_test_case.php' );
	require_once( dirname( __FILE__ ).'/../../app_controller.php' );
	
	class ItemsController extends AppController {
		public $name = 'Items';
		public $uses = array( 'Item' );
		public $components = array( 'TestDefault' );

		public $autoRender = false;
		public $redirectUrl;
		public $redirectStatus;
		public $renderedAction;
		public $renderedLayout;
		public $renderedFile;
		public $stopped;
		public $condition;
		public $error;
		public $parameters;

		public function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
			$this->redirectStatus = $status;
		}

		/**
		*
		*/
		public function render($action = null, $layout = null, $file = null) {
			$this->renderedAction = $action;
			$this->renderedLayout = (is_null($layout) ? $this->layout : $layout);
			$this->renderedFile = $file;
		}

		/**
		*
		*/
		public function assert( $condition, $error = 'error500', $parameters = array() ) {
			$this->condition = $condition;
			$this->error = $error;
			$this->parameters = $parameters;
		}
	}

	class CakeAppComponentTestCase extends CakeAppTestCase
	{
		/**
		* Here we instantiate our helper, all other helpers we need,
		* and a View class.
		*/
		public function startTest() {
			ClassRegistry::config( array( 'ds' => 'test_suite' ) );
			$this->Items =& new ItemsController();
			$this->Items->constructClasses();
			$this->Items->Component->initialize( $this->Items );
		}

		/**
		* Exécuté après chaque test.
		*/
		public function tearDown() {
			unset( $this->Items );
 			ClassRegistry::flush();
		}
	}
?>
