<?php
	/**
	 * Code source de la classe DefaultDataHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'DefaultDataHelper', 'Default.View/Helper' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );

	/**
	 * La classe DefaultDataHelperTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 */
	class DefaultDataHelperTest extends DefaultAbstractTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple'
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
			$this->DefaultData = new DefaultDataHelper( $this->View );

			$this->DefaultData->request = new CakeRequest( null, false );
			$this->DefaultData->request->addParams( array( 'controller' => 'apples', 'action' => 'index' ) );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->DefaultData );
		}

		/**
		 * Test de la méthode DefaultDataHelper::cacheKey()
		 *
		 * @return void
		 */
		public function testCacheKey() {
			$result = $this->DefaultData->cacheKey();
			$expected = 'DefaultDataHelper_Apples_index';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultDataHelper::type()
		 *
		 * @return void
		 */
		public function testType() {
			$result = $this->DefaultData->type( 'Apple.color' );
			$expected = 'string';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->type( 'Apple.foo' );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->type( 'Foo.bar' );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultDataHelper::format()
		 *
		 * @return void
		 */
		public function testFormat() {
			$result = $this->DefaultData->format( null, 'foo' );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->format( 'red', 'string' );
			$expected = 'red';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->format( 1000, 'integer' );
			$expected = '1,000';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->format( true, 'boolean' );
			$expected = __( 'Yes' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->format( false, 'boolean' );
			$expected = __( 'No' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->format( '2013-06-01', 'date' );
			$expected = '01/06/2013';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->format( '2013-06-01 10:02:58', 'datetime' );
			$expected = '01/06/2013 à 10:02:58';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultDataHelper::attributes()
		 *
		 * @return void
		 */
		public function testAttributes() {
			$result = $this->DefaultData->attributes( null, 'foo' );
			$expected = array( 'class' => 'data foo' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->attributes( 0, 'integer' );
			$expected = array( 'class' => 'data integer zero' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->attributes( 1000, 'integer' );
			$expected = array( 'class' => 'data integer positive' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->attributes( -666.66, 'numeric' );
			$expected = array( 'class' => 'data numeric negative' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->attributes( true, 'boolean' );
			$expected = array( 'class' => 'data boolean true' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultData->attributes( false, 'boolean' );
			$expected = array( 'class' => 'data boolean false' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test du cache via les méthodes DefaultDataHelper::cacheKey(),
		 *  DefaultDataHelper::beforeRender() et DefaultDataHelper::afterLayout().
		 */
		public function testAfterLayout() {
			Configure::write( 'Cache.disable', false );

			$this->DefaultData->type( 'Apple.color' );

			$layoutFile = APP.'View/Layouts/default.ctp';
			$this->DefaultData->afterLayout( $layoutFile );

			$viewFile = APP.'View/Cataloguespdisfps93/add_edit.ctp';
			$this->DefaultData->beforeRender( $viewFile );

			$result = Cache::read( $this->DefaultData->cacheKey() );
			$expected = array(
				'Apple' => array(
					'id' => 'integer',
					'apple_id' => 'integer',
					'color' => 'string',
					'name' => 'string',
					'created' => 'datetime',
					'date' => 'date',
					'modified' => 'datetime',
					'mytime' => 'time'
				)
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>