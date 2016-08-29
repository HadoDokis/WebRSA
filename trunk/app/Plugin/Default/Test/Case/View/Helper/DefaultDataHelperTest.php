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

			// Liste de valeurs
			$result = $this->DefaultData->format( "-0402\n\r-0404\n\r-0405", 'list' );
			$expected = array( '0402', '0404', '0405' );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Valeur vide / nulle
			$result = $this->DefaultData->format( '', 'string' );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Datetime
			$result = $this->DefaultData->format( '2013-06-01 11:05:55', 'datetime' );
			$expected = '01/06/2013 à 11:05:55';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Time
			$result = $this->DefaultData->format( '11:05:55', 'time' );
			$expected = '11:05:55';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Truncate
			$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
			$result = $this->DefaultData->format( $text, 'text', 'truncate' );
			$expected = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolo...';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultDataHelper::format() avec utilisation du
		 * paramètre format.
		 *
		 * @return void
		 */
		public function testFormatParamFormat() {
			// 1. Valeur nulle
			$result = $this->DefaultData->format( null, 'date', '%B %Y' );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// 2. Date
			$result = $this->DefaultData->format( '2013-06-01', 'date', '%B %Y' );
			$expected = 'juin 2013';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Datetime
			$result = $this->DefaultData->format( '2013-06-01 11:05:55', 'datetime', '%A %e %B %Y %H:%M' );
			$expected = 'samedi  1 juin 2013 11:05';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Time
			$result = $this->DefaultData->format( '11:05', 'time', '%H:%M' );
			$expected = '11:05';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultDataHelper::attributes()
		 *
		 * @return void
		 */
		public function testAttributes() {
			$result = $this->DefaultData->attributes( null, 'foo' );
			$expected = array( 'class' => 'data foo null' );
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

			// Liste de valeurs
			$result = $this->DefaultData->attributes( "-0402\n\r-0404\n\r-0405", 'list' );
			$expected = array( 'class' => 'data list text' );
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

		/**
		 * Test de la méthode DefaultDataHelper::translateOptions().
		 */
		public function testTranslateOptions() {
			$params = array(
				'options' => array(
					'5' => 'MyOpt1',
					'6' => 'MyOpt2'
				)
			);
			// 1. Chaîne de caractères
			$result = $this->DefaultData->translateOptions( '5', $params );
			$expected = 'MyOpt1';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// 2. Array
			$result = $this->DefaultData->translateOptions( array( '5', '6' ), $params );
			$expected = array(
				'MyOpt1',
				'MyOpt2'
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>