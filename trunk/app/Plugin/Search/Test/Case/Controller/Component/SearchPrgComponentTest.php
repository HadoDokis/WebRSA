<?php
	/**
	 * Fichier source de la classe SearchPrgComponentTest
	 *
	 * PHP 5.3
	 * @package Search
	 * @subpackage Test.Case.Controller.Component
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'RequestHandlerComponent', 'Controller/Component' );
	App::uses( 'SearchPrgComponent', 'Controller/Component' );
	App::uses( 'CakeRequest', 'Network' );
	App::uses( 'CakeResponse', 'Network' );
	App::uses( 'Router', 'Routing' );

	/**
	 * SearchPrgTestController class
	 *
	 * @package Search
	 * @subpackage Test.Case.Controller.Component
	 */
	class SearchPrgTestController extends Controller
	{

		/**
		 * name property
		 *
		 * @var string 'SearchPrgTest'
		 */
		public $name = 'SearchPrgTest';

		/**
		 * uses property
		 *
		 * @var mixed null
		 */
		public $uses = null;

		/**
		 * components property
		 *
		 * @var array
		 */
		public $components = array(
			'Search.SearchPrg' => array(
				'actions' => array( 'index' => array( 'filter' => 'Search' ) ),
			)
		);


		/**
		 *
		 * @param string|array $url A string or array-based URL pointing to another location within the app,
		 *     or an absolute URL
		 * @param integer $status Optional HTTP status code (eg: 404)
		 * @param boolean $exit If true, exit() will be called after the redirect
		 * @return mixed void if $exit = false. Terminates script if $exit = true
		 */
		public function redirect( $url, $status = null, $exit = true) {
			$this->redirected = array( $url, $status, $exit );
			return false;
		}

	}
	/**
	 * SearchPrgTest class
	 *
	 * @package       app.Plugin.Search.Test.Case.Controller.Component
	 */
	class SearchPrgComponentTest extends CakeTestCase
	{

		/**
		 * Controller property
		 *
		 * @var SearchPrgTestController
		 */
		public $Controller;

		/**
		 * name property
		 *
		 * @var string 'SearchPrg'
		 */
		public $name = 'SearchPrg';

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();

			$request = new CakeRequest( 'prgs/index', false );
			$request->addParams(array( 'controller' => 'prgs', 'action' => 'index' ) );
			$this->Controller = new SearchPrgTestController( $request );
			$this->Controller->Components->init( $this->Controller );
			$this->Controller->SearchPrg->initialize( $this->Controller );
		}

		/**
		 * testRedirect method
		 *
		 * @return void
		 */
		public function testPostRedirect() {
			$_SERVER['REQUEST_METHOD'] = 'POST';
			$data = array(
				'Search' => array(
					'active' => true,
					'User' => array(
						'username' => 'john'
					)
				)
			);
			$this->Controller->data = $data;
			$this->Controller->SearchPrg->startup( $this->Controller );
			$result = $this->Controller->redirected;
			$expected = array(
				array(
					'action' => 'index',
					'Search__active' => '1',
					'Search__User__username' => 'john',
				),
				null,
				true,
			);

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * testRedirect method
		 *
		 * @return void
		 */
		public function testPostRedirectWithFormParams() {
			$_SERVER['REQUEST_METHOD'] = 'POST';
			$this->Controller->request->params['form'] = array(
				'Foo' => 'bar'
			);
			$data = array(
				'Search' => array(
					'active' => true,
					'User' => array(
						'username' => 'john'
					)
				)
			);
			$this->Controller->data = $data;
			$this->Controller->SearchPrg->startup( $this->Controller );
			$result = $this->Controller->redirected;
			$expected = null;

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * testGetPostParams method
		 *
		 * @return void
		 */
		public function testGetPostParams() {
			$_SERVER['REQUEST_METHOD'] = 'GET';
			$this->Controller->request->params['named'] = array(
				'Search__active' => '1',
				'Search__User__username' => 'john'
			);
			$this->Controller->SearchPrg->startup( $this->Controller );
			$result = $this->Controller->data;
			$expected = array(
				'Search' => array(
					'active' => true,
					'User' => array(
						'username' => 'john'
					)
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * TODO: passe dans un navigateur, pas en console.
		 * testRedirect method
		 *
		 * @return void
		 */
		public function testPostRedirectFilter() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			$_SERVER['REQUEST_METHOD'] = 'POST';
			$data = array(
				'Search' => array(
					'active' => true,
					'User' => array(
						'username' => 'john'
					)
				),
				'Orientstruct' => array(
					'foo' => 'bar'
				)
			);
			$this->Controller->data = $data;
			$this->Controller->SearchPrg->startup( $this->Controller );
			$result = $this->Controller->redirected;

			$SearchPrg = $this->Controller->SearchPrg;
			$prgSessionKey = "{$SearchPrg->name}.{$this->Controller->name}__{$this->Controller->action}";
			$sessionKeys = array_keys( $SearchPrg->Session->read( $prgSessionKey ) );
			$sessionKey = $sessionKeys[0];

			$expected = array(
				array(
					'action' => 'index',
					'Search__active' => '1',
					'Search__User__username' => 'john',
					'sessionKey' => $sessionKey,
				),
				null,
				true
			);

			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $SearchPrg->Session->read( "{$prgSessionKey}.{$sessionKey}" );
			$expected = array(
				'Orientstruct' => array(
					'foo' => 'bar'
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * TODO: passe dans un navigateur, pas en console.
		 * testRedirect method
		 *
		 * @return void
		 */
		public function testGetFilter() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			$_SERVER['REQUEST_METHOD'] = 'GET';
			$prgSessionKey = "{$this->Controller->SearchPrg->name}.{$this->Controller->name}__{$this->Controller->action}";
			$sessionKey = '62cdb7020ff920e5aa642c3d4066950dd1f01f4d';
			$this->Controller->SearchPrg->Session->write( "{$prgSessionKey}.{$sessionKey}", array( 'Foo' => 'bar' ) );

			$this->Controller->request->params['named'] = array(
				'Search__active' => '1',
				'Search__User__username' => 'john',
				'sessionKey' => $sessionKey
			);

			$this->Controller->SearchPrg->startup( $this->Controller );
			$result = $this->Controller->data;
			$expected = array(
				'Search' => array(
					'active' => true,
					'User' => array(
						'username' => 'john'
					)
				),
				'sessionKey' => $sessionKey,
				'Foo' => 'bar'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>