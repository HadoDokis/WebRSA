<?php
	/**
	 * Code source de la classe DefaultUtilityTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUtility', 'Default.Utility' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );
	App::uses( 'Router', 'Routing' );

	/**
	 * La classe DefaultUtilityTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 */
	class DefaultUtilityTest extends DefaultAbstractTestCase
	{
		/**
		 * Données utilisées pour l'évaluation.
		 *
		 * @var array
		 */
		public $data = array(
			'User' => array(
				'id' => 6,
				'username' => 'foo',
				'lastname' => 'bar',
			)
		);

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
					'action' => 'index',
					'plugin' => null,
					'controller' => 'subscribe',
					'admin' => true
				)
			);
			$request->base = '/magazine';
			$request->here = '/magazine';
			$request->webroot = '/magazine/';
			Router::setRequestInfo( $request );
		}

		/**
		 * Test de la méthode DefaultUtility::evaluateString()
		 *
		 * @return void
		 */
		public function testEvaluateString() {
			$result = DefaultUtility::evaluateString( $this->data, '#User.username#' );
			$expected = 'foo';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::evaluateString( $this->data, '#User.username# is a #User.lastname#' );
			$expected = 'foo is a bar';
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::evaluate()
		 *
		 * @return void
		 */
		public function testEvaluate() {
			$evaluated = array(
				'fuu#User.lastname#baz' => array(
					'#User.id#' => array(
						'#User.username#.#User.lastname#'
					)
				)
			);

			$result = DefaultUtility::evaluate( $this->data, $evaluated );
			$expected = array(
				'fuubarbaz' => array(
					'6' => array(
						'foo.bar'
					)
				)
			);
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::linkParams()
		 *
		 * @return void
		 */
		public function testLinkParams() {
			$evaluated = array(
				'fuu#User.lastname#baz' => array(
					'#User.id#' => array(
						'#User.username#.#User.lastname#'
					)
				)
			);

			// TODO: en faire 3 fonctions (avec un cache), dans une classe utilitaire séparée, genre DefaultUrlConverter
			$result = DefaultUtility::linkParams( '/AclUtilities.Users/admin_edit/#User.id##content', array( 'title' => true, 'confirm' => true ), $this->data );
			$expected = array(
				'/AclUtilities.Users/admin_edit',
				array(
					'plugin' => 'acl_utilities',
					'controller' => 'users',
					'action' => 'edit',
					'6',
					'prefix' => 'admin',
					'admin' => true,
					'#' => 'content'
				),
				array(
					'title' => '/AclUtilities.Users/admin_edit/6#content',
					'confirm' => '/AclUtilities.Users/admin_edit/6#content ?',
				),
			);
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::toString()
		 *
		 * @return void
		 */
		public function testToString() {
			$result = DefaultUtility::toString( array( 'controller' => 'foos', 'action' => 'bar' ) );
			$expected = '/Foos/bar';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toString( '/Foos/bar' );
			$expected = '/Foos/bar';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toString( array( 'controller' => 'foos', 'action' => 'bar', 'Search__active' => 1 ) );
			$expected = '/Foos/bar/Search__active:1';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toString( '/Foos/bar/Search__active:1' );
			$expected = '/Foos/bar/Search__active:1';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toString( array( 'plugin' => 'tests','controller' => 'foos', 'prefix' => 'admin', 'admin' => true, 'action' => 'bar', 0 => 666, 'Search__active' => 1 ) );
			$expected = '/Tests.Foos/admin_bar/666/Search__active:1';
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::toArray()
		 *
		 * @return void
		 */
		public function testToArray() {
			$result = DefaultUtility::toArray( '/Foos/bar' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar' );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toArray( '/Foos/bar/Search__active:1' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar', 'Search__active' => 1 );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toArray( '/Tests.Foos/admin_bar/666/Search__active:1' );
			$expected = array( 'plugin' => 'tests','controller' => 'foos', 'prefix' => 'admin', 'admin' => true, 'action' => 'bar', 0 => 666, 'Search__active' => 1 );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toArray( '/Foos/bar#6' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar', '#' => 6 );
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::toArray( '/Foos/bar##Model.field#' );
			$expected = array( 'plugin' => null, 'controller' => 'foos', 'action' => 'bar', '#' => '#Model.field#' );
			$this->assertEqual( $result, $expected, $result );
		}
	}
?>