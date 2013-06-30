<?php
	/**
	 * Code source de la classe DefaultUrlTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUrl', 'Default.Utility' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );
	App::uses( 'Router', 'Routing' );

	/**
	 * La classe DefaultUrlTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 */
	class DefaultUrlTest extends DefaultAbstractTestCase
	{
		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();

			$request = new CakeRequest();
			$request->addParams(
				array(
					'plugin' => null,
					'controller' => 'users',
					'action' => 'login',
//					'admin' => true
				)
			);
//			$request->base = '/magazine';
//			$request->here = '/magazine';
//			$request->webroot = '/magazine/';
			Router::setRequestInfo( $request );
		}

		/**
		 * Test de la méthode DefaultUrl::toString()
		 *
		 * @return void
		 */
		public function testToString() {
			$url = array( 'action' => 'logout' );
			$result = DefaultUrl::toString( $url );
			$expected = '/Users/logout';
			$this->assertEqual( $result, $expected, $result );

			$url = array( 'action' => 'index', '#' => 'results' );
			$result = DefaultUrl::toString( $url );
			$expected = '/Users/index/#results';
			$this->assertEqual( $result, $expected, $result );

			$url = array( 'plugin' => 'plugin', 'controller' => 'controllers', 'action' => 'action', 'prefix' => 'prefix', 0 => 'param1', 'named' => 'value' );
			$result = DefaultUrl::toString( $url );
			$expected = '/Plugin.Controllers/prefix_action/param1/named:value';
			$this->assertEqual( $result, $expected, $result );

			$url = array( 'plugin' => 'plugin', 'controller' => 'controllers', 'action' => 'action', 'prefix' => 'prefix', 0 => 'param1', '#' => 'anchor,subanchor', 'named' => 'value' );
			$result = DefaultUrl::toString( $url );
			$expected = '/Plugin.Controllers/prefix_action/param1/named:value#anchor,subanchor';
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUrl::toString()
		 *
		 * @return void
		 */
		public function testToString2() {
			$result = DefaultUrl::toString( array( 'controller' => 'foos', 'action' => 'bar' ) );
			$expected = '/Foos/bar';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toString( '/Foos/bar' );
			$expected = '/Foos/bar';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toString( array( 'controller' => 'foos', 'action' => 'bar', 'Search__active' => 1 ) );
			$expected = '/Foos/bar/Search__active:1';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toString( '/Foos/bar/Search__active:1' );
			$expected = '/Foos/bar/Search__active:1';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toString( array( 'plugin' => 'tests','controller' => 'foos', 'prefix' => 'admin', 'admin' => true, 'action' => 'bar', 0 => 666, 'Search__active' => 1 ) );
			$expected = '/Tests.Foos/admin_bar/666/Search__active:1';
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUrl::toArray()
		 *
		 * @return void
		 */
		public function testToArray() {
			$url = '/Users/logout';
			$result = DefaultUrl::toArray( $url );
			$expected = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'logout'
			);
			$this->assertEqual( $result, $expected, $result );

			$url = '/Users/index#results';
			$result = DefaultUrl::toArray( $url );
			$expected = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'index',
				'#' => 'results'
			);
			$this->assertEqual( $result, $expected, $result );

			$url = '/Users/index/#results';
			$result = DefaultUrl::toArray( $url );
			$expected = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'index',
				'#' => 'results'
			);
			$this->assertEqual( $result, $expected, $result );

			$url = '/Plugin.Controllers/admin_action/param1/named:value';
			$result = DefaultUrl::toArray( $url );
			$expected = array(
				'prefix' => 'admin',
				'plugin' => 'plugin',
				'controller' => 'controllers',
				'action' => 'action',
				0 => 'param1',
				'admin' => true,
				'named' => 'value'
			);
			$this->assertEqual( $result, $expected, $result );

			$url = '/Plugin.Controllers/prefix_action/param1/named:value/#anchor,subanchor';
			$result = DefaultUrl::toArray( $url );
			$expected = array(
				'plugin' => 'plugin',
				'controller' => 'controllers',
				'action' => 'prefix_action',
				0 => 'param1',
				'named' => 'value',
				'#' => 'anchor,subanchor'
			);
			$this->assertEqual( $result, $expected, $result );
		}


		/**
		 * Test de la méthode DefaultUrl::toArray()
		 *
		 * @return void
		 */
		public function testToArray2() {
			$result = DefaultUrl::toArray( '/Foos/bar' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar' );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toArray( '/Foos/bar/Search__active:1' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar', 'Search__active' => 1 );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toArray( '/Tests.Foos/admin_bar/666/Search__active:1' );
			$expected = array( 'plugin' => 'tests','controller' => 'foos', 'prefix' => 'admin', 'admin' => true, 'action' => 'bar', 0 => 666, 'Search__active' => 1 );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toArray( '/Foos/bar#6' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar', '#' => 6 );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUrl::toArray( '/Foos/bar##Model.field#' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar', '#' => '#Model.field#' );
			$this->assertEqual( $result, $expected, $result );
		}
	}
?>