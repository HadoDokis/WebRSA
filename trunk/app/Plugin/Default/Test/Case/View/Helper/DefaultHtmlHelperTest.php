<?php
	/**
	 * Code source de la classe DefaultHtmlHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'DefaultHtmlHelper', 'Default.View/Helper' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );

	/**
	 * La classe DefaultHtmlHelperTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 */
	class DefaultHtmlHelperTest extends DefaultAbstractTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
		);

		/**
		 * Préparation du test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$controller = null;
			$this->View = new View( $controller );
			$this->DefaultHtml = new DefaultHtmlHelper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->DefaultHtml );
		}

		/**
		 * Test de la méthode DefaultHtmlHelper::link()
		 *
		 * @return void
		 */
		public function testLink() {
			$url = array(
				'plugin' => 'default',
				'controller' => 'users',
				'action' => 'add',
				'prefix' => 'admin',
				'admin' => true
			);

			$result = $this->DefaultHtml->link( 'Test', $url );
			$expected = '<a href="/admin/default/users/add" class="default users admin_add">Test</a>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultHtml->link( 'Test', $url, array( 'disabled' => true ) );
			$expected = '<span class="default users admin_add link disabled">Test</span>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>