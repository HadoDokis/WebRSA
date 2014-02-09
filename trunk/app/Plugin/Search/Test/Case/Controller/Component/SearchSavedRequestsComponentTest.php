<?php
	/**
	 * Fichier source de la classe SearchSavedRequestsComponentTest
	 *
	 * PHP 5.3
	 * @package Search
	 * @subpackage Test.Case.Controller.Component
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'RequestHandlerComponent', 'Controller/Component' );
	App::uses( 'SearchSavedRequestsComponent', 'Controller/Component' );
	App::uses( 'CakeRequest', 'Network' );
	App::uses( 'CakeResponse', 'Network' );
	App::uses( 'Router', 'Routing' );

	/**
	 * SearchSavedRequestsTestController class
	 *
	 * @package Search
	 * @subpackage Test.Case.Controller.Component
	 */
	class SearchSavedRequestsTestController extends Controller
	{

		/**
		 * name property
		 *
		 * @var string 'SearchSavedRequestsTest'
		 */
		public $name = 'SearchSavedRequestsTest';

		/**
		 * uses property
		 *
		 * @var mixed null
		 */
		public $uses = null;

		/**
		 * Les paramètres de redirection.
		 *
		 * @var array
		 */
		public $redirected = null;

		/**
		 * components property
		 *
		 * @var array
		 */
		public $components = array(
			'Search.SearchSavedRequests'
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
	 * SearchSavedRequestsTest class
	 *
	 * @package       app.Plugin.Search.Test.Case.Controller.Component
	 */
	class SearchSavedRequestsComponentTest extends CakeTestCase
	{

		/**
		 * Controller property
		 *
		 * @var SearchSavedRequestsTestController
		 */
		public $Controller;

		/**
		 * name property
		 *
		 * @var string 'SearchSavedRequests'
		 */
		public $name = 'SearchSavedRequests';

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();

			$request = new CakeRequest( 'prgs/index', false );
			$request->addParams(array( 'controller' => 'prgs', 'action' => 'index' ) );
			$this->Controller = new SearchSavedRequestsTestController( $request );
			$this->Controller->Components->init( $this->Controller );
			$this->Controller->SearchSavedRequests->initialize( $this->Controller );
		}

		/**
		 * Test de la méthode SearchSavedRequestsComponent::sessionKey()
		 */
		public function testSessionKey() {
			$result = $this->Controller->SearchSavedRequests->sessionKey( 'users', 'index' );
			$expected = 'SearchSavedRequests.Users.index';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Controller->SearchSavedRequests->name = 'Foo';
			$result = $this->Controller->SearchSavedRequests->sessionKey( 'users', 'index' );
			$expected = 'Foo.Users.index';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchSavedRequestsComponent::write()
		 */
		public function testWrite() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			$url = array(
				'plugin' => null,
				'controller' => 'searches',
				'action' => 'index',
				'named' => array(
					'Foo' => 'bar'
				)
			);
			$result = $this->Controller->SearchSavedRequests->write( 'users', 'index', $url );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Controller->SearchSavedRequests->Session->read( 'SearchSavedRequests.Users.index' );
			$expected = array(
				'plugin' => null,
				'controller' => 'searches',
				'action' => 'index',
				'Foo' => 'bar'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchSavedRequestsComponent::read()
		 */
		public function testRead() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			$url = array(
				'plugin' => null,
				'controller' => 'searches',
				'action' => 'index',
				'named' => array(
					'Foo' => 'bar'
				)
			);
			$this->Controller->SearchSavedRequests->write( 'users', 'index', $url );

			$result = $this->Controller->SearchSavedRequests->read( 'users', 'index' );
			$expected = array(
				'plugin' => null,
				'controller' => 'searches',
				'action' => 'index',
				'Foo' => 'bar'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchSavedRequestsComponent::check()
		 */
		public function testCheck() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			$url = array(
				'plugin' => null,
				'controller' => 'searches',
				'action' => 'index',
				'named' => array(
					'Foo' => 'bar'
				)
			);
			$this->Controller->SearchSavedRequests->write( 'users', 'index', $url );

			$result = $this->Controller->SearchSavedRequests->check( 'users', 'index' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchSavedRequestsComponent::delete()
		 */
		public function testDelete() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			$url = array(
				'plugin' => null,
				'controller' => 'searches',
				'action' => 'index',
				'named' => array(
					'Foo' => 'bar'
				)
			);
			$this->Controller->SearchSavedRequests->write( 'users', 'index', $url );

			$result = $this->Controller->SearchSavedRequests->check( 'users', 'index' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Controller->SearchSavedRequests->delete( 'users', 'index' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Controller->SearchSavedRequests->check( 'users', 'index' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchSavedRequestsComponent::redirect()
		 */
		public function testRedirect() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			$url = array(
				'plugin' => null,
				'controller' => 'searches',
				'action' => 'index',
				'named' => array(
					'Foo' => 'bar'
				)
			);
			$this->Controller->SearchSavedRequests->write( 'users', 'index', $url );

			$default = array( 'controller' => 'users', 'action' => 'index' );

			// Premier appel, redirection vers l'URL enregistrée
			$result = $this->Controller->SearchSavedRequests->redirect( 'users', 'index', $default );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Controller->redirected;
			$expected = array(
				array(
					'plugin' => null,
					'controller' => 'searches',
					'action' => 'index',
					'Foo' => 'bar'
				),
				null,
				true
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// Second appel, redirection vers default
			$result = $this->Controller->SearchSavedRequests->redirect( 'users', 'index', $default );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Controller->redirected;
			$expected = array(
				array(
					'plugin' => NULL,
					'controller' => 'users',
					'action' => 'index',
				),
				null,
				true,
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>