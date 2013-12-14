<?php
	/**
	 * SearchProgressivePaginationTest file
	 *
	 * PHP 5.3
	 *
	 * @package Validation
	 * @subpackage Test.Case.Utility.Validation2Formatters
	 */
	App::uses( 'SearchProgressivePagination', 'Utility' );

	/**
	 * SearchProgressivePaginationTest class
	 *
	 * @package Validation
	 * @subpackage Test.Case.Utility.Validation2Formatters
	 */
	class SearchProgressivePaginationTest extends CakeTestCase
	{
		/**
		 * Préparation de l'environnement d'une méthode de test.
		 */
		public function setUp() {
			parent::setUp();
			Configure::delete( 'Optimisations' );
		}
		/**
		 * Test de la méthode SearchProgressivePagination::configureKey();
		 */
		public function testConfigureKey() {
			$result = SearchProgressivePagination::configureKey();
			$expected = 'Optimisations.progressivePaginate';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = SearchProgressivePagination::configureKey( 'my_controller_name' );
			$expected = 'Optimisations.MyControllerName.progressivePaginate';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = SearchProgressivePagination::configureKey( 'MyControllerName', 'my_action' );
			$expected = 'Optimisations.MyControllerName_my_action.progressivePaginate';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = SearchProgressivePagination::configureKey( null, 'my_action' );
			$expected = 'Optimisations.progressivePaginate';
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchProgressivePagination::enabled();
		 */
		public function testEnabled() {
			Configure::write( 'Optimisations.MyControllerName_my_action.progressivePaginate', true );
			$result = SearchProgressivePagination::enabled( 'MyControllerName', 'my_action' );
			$this->assertEquals( true, $result, var_export( $result, true ) );

			Configure::write( 'Optimisations.MyControllerName.progressivePaginate', true );
			$result = SearchProgressivePagination::enabled( 'MyControllerName', 'my_action' );
			$this->assertEquals( true, $result, var_export( $result, true ) );

			$result = SearchProgressivePagination::enabled( 'MyControllerName' );
			$this->assertEquals( true, $result, var_export( $result, true ) );

			Configure::write( 'Optimisations.progressivePaginate', true );
			$result = SearchProgressivePagination::enabled( 'MyControllerName', 'my_action2' );
			$this->assertEquals( true, $result, var_export( $result, true ) );

			$result = SearchProgressivePagination::enabled( 'MyControllerName' );
			$this->assertEquals( true, $result, var_export( $result, true ) );

			$result = SearchProgressivePagination::enabled();
			$this->assertEquals( true, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchProgressivePagination::enable();
		 */
		public function testEnable() {
			SearchProgressivePagination::enable( 'MyControllerName', 'my_action' );
			$result = SearchProgressivePagination::enabled( 'MyControllerName', 'my_action' );
			$this->assertEquals( true, $result, var_export( $result, true ) );

			SearchProgressivePagination::enable();
			$result = SearchProgressivePagination::enabled();
			$this->assertEquals( true, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchProgressivePagination::disable();
		 */
		public function testDisable() {
			SearchProgressivePagination::enable();

			SearchProgressivePagination::disable( 'MyControllerName', 'my_action' );
			$result = SearchProgressivePagination::enabled( 'MyControllerName', 'my_action' );
			$this->assertEquals( false, $result, var_export( $result, true ) );
		}
	}
?>