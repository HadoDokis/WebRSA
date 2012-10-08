<?php
	/**
	 * Validation.ExtraValidationRulesBehaviorTest file
	 *
	 * PHP 5.3
	 *
	 * @package Validation
	 * @subpackage Test.Case.Model.Behavior
	 */
	App::uses( 'Model', 'Model' );
	App::uses( 'AppModel', 'Model' );

	/**
	 * Validation.ExtraValidationRulesTest class
	 *
	 * @package Validation
	 * @subpackage Test.Case.Model.Behavior
	 */
	class Validation.ExtraValidationRulesBehaviorTest extends CakeTestCase
	{
		/**
		 * Fixtures associated with this test case
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.site'
		);

		/**
		 * Method executed before each test
		 *
		 */
		public function setUp() {
			parent::setUp();
			$this->Site = ClassRegistry::init( 'Site' );
			$this->Site->Behaviors->attach( 'Validation.Validation.ExtraValidationRules' );
		}

		/**
		 * Method executed after each test
		 *
		 */
		public function tearDown() {
			unset( $this->Site );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::exactLength
		 *
		 * @return void
		 */
		public function testExactLength() {
			$result = $this->Site->exactLength( null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->exactLength( array( 'foo' => '15' ), 2 );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->exactLength( array( 'foo' => 15 ), 2 );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );


			$result = $this->Site->exactLength( array( 'foo' => 'bar' ), 2 );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::futureDate
		 *
		 * @return void
		 */
		public function testFutureDate() {
			$result = $this->Site->futureDate( null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->futureDate( array( 'foo' => date( 'Y-m-d', strtotime( '+1 day' ) ) ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->futureDate( array( 'foo' => date( 'Y-m-d' ) ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->futureDate( array( 'foo' => date( 'Y-m-d', strtotime( '-1 day' ) ) ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::datePassee
		 *
		 * @return void
		 */
		public function testDatePassee() {
			$result = $this->Site->datePassee( null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->datePassee( array( 'foo' => date( 'Y-m-d', strtotime( '-1 day' ) ) ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->datePassee( array( 'foo' => date( 'Y-m-d' ) ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->datePassee( array( 'foo' => date( 'Y-m-d', strtotime( '+1 day' ) ) ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::phoneFr
		 *
		 * @return void
		 */
		public function testPhoneFr() {
			$result = $this->Site->phoneFr( null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->phoneFr( array( 'phone' => '9999999999' ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->phoneFr( array( 'phone' => '04 09 80 15 09' ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::allEmpty
		 *
		 * @return void
		 */
		public function testAllEmpty() {
			$result = $this->Site->allEmpty( null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->allEmpty( array( 'phone' => '', 'fax' => null ), 'fax' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->allEmpty( array( 'phone' => ' ', 'fax' => null ), 'fax' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::notEmptyIf
		 *
		 * @return void
		 */
		public function testNotEmptyIf() {
			$result = $this->Site->notEmptyIf( null, null, null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->notEmptyIf( array( 'phone' => 'X', 'fax' => null ), 'fax', true, array( null ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->notEmptyIf( array( 'phone' => '', 'fax' => null ), 'fax', true, array( null ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::greaterThanIfNotZero
		 *
		 * @return void
		 */
		public function testGreaterThanIfNotZero() {
			$result = $this->Site->greaterThanIfNotZero( null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'phone' => 1, 'fax' => 1 ) );
			$result = $this->Site->greaterThanIfNotZero( array( 'phone' => 1 ), 'fax' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'phone' => 1, 'fax' => 2 ) );
			$result = $this->Site->greaterThanIfNotZero( array( 'phone' => 1 ), 'fax' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::compareDates
		 *
		 * @return void
		 */
		public function testCompareDates() {
			$result = $this->Site->compareDates( null, null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'from' => null, 'to' => null ) );
			$result = $this->Site->compareDates( array( 'from' => null ), 'to', 'null' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'from' => '20120101', 'to' => '20120102' );
			$this->Site->create( $data );

			$result = $this->Site->compareDates( array( 'from' => $data['from'] ), 'to', '<' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->compareDates( array( 'from' => $data['from'] ), 'to', '*' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->compareDates( array( 'from' => $data['from'] ), 'to', '>' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation.ExtraValidationRules::inclusiveRange
		 *
		 * @return void
		 */
		public function testInclusiveRange() {
			$result = $this->Site->inclusiveRange( null, null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->inclusiveRange( array( 'value' => 5 ), 0, 5 );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>