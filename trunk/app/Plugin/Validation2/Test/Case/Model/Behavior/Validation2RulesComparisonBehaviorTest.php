<?php
	/**
	 * Validation2RulesComparisonBehaviorTest file
	 *
	 * PHP 5.3
	 *
	 * @package Validation2
	 * @subpackage Test.Case.Model.Behavior
	 */
	App::uses( 'Model', 'Model' );
	App::uses( 'AppModel', 'Model' );

	/**
	 * ExtraBasicValidation2RulesTest class
	 *
	 * @package Validation2
	 * @subpackage Test.Case.Model.Behavior
	 */
	class Validation2RulesComparisonBehaviorTest extends CakeTestCase
	{
		/**
		 * Fixtures associated with this test case
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Validation2.Validation2Site'
		);

		/**
		 * Method executed before each test
		 */
		public function setUp() {
			parent::setUp();
			$this->Site = ClassRegistry::init(
				array(
					'class' => 'Validation2.Validation2Site',
					'alias' => 'Site',
					'ds' => 'test',
				)
			);
			$this->Site->Behaviors->attach( 'Validation2.Validation2RulesComparison' );
		}

		/**
		 * Method executed after each test
		 */
		public function tearDown() {
			unset( $this->Site );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Validation2RulesComparisonBehavior::allEmpty() du
		 * plugin Validation2.
		 *
		 * @return void
		 */
		public function testAllEmpty() {
			$result = $this->Site->allEmpty( null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => '', 'fax' => null );
			$this->Site->create( $data );
			$result = $this->Site->allEmpty( array( 'phone' => '' ), 'fax' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => ' ', 'fax' => null );
			$this->Site->create( $data );
			$result = $this->Site->allEmpty( array( 'phone' => ' ' ), 'fax' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation2RulesComparisonBehavior::notEmptyIf() du
		 * plugin Validation2.
		 *
		 * @return void
		 */
		public function testNotEmptyIf() {
			$result = $this->Site->notEmptyIf( null, null, null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => 'X', 'fax' => null );
			$this->Site->create( $data );
			$result = $this->Site->notEmptyIf( array( 'phone' => 'X' ), 'fax', true, array( null ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => '', 'fax' => null );
			$this->Site->create( $data );
			$result = $this->Site->notEmptyIf( array( 'phone' => '' ), 'fax', true, array( null ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => '', 'fax' => 'P' );
			$this->Site->create( $data );
			$result = $this->Site->notEmptyIf( array( 'phone' => '' ), 'fax', true, array( 'P' ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => '', 'fax' => 'F' );
			$this->Site->create( $data );
			$result = $this->Site->notEmptyIf( array( 'phone' => '' ), 'fax', true, array( 'P' ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation2RulesComparisonBehavior::notNullIf()
		 *
		 * @return void
		 */
		public function testNotNullIf() {
			$result = $this->Site->notNullIf( null, null, null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => '0606060606', 'fax' => null );
			$this->Site->create( $data );
			$result = $this->Site->notNullIf(
				array( 'phone' => '0606060606' ),
				'fax',
				true,
				array( null )
			);
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => null, 'fax' => null );
			$this->Site->create( $data );
			$result = $this->Site->notNullIf(
				array( 'phone' => null ),
				'fax',
				false,
				array( null )
			);
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'phone' => '0606060606', 'fax' => '0404040404' );
			$this->Site->create( $data );
			$result = $this->Site->notNullIf(
				array( 'phone' => '0606060606' ),
				'fax',
				true,
				array( null )
			);
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation2RulesComparisonBehavior::greaterThanIfNotZero
		 *
		 * @return void
		 */
		public function testGreaterThanIfNotZero() {
			$result = $this->Site->greaterThanIfNotZero( null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'value' => 1, 'reference' => 1 ) );
			$result = $this->Site->greaterThanIfNotZero( array( 'value' => 1 ), 'reference' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'value' => 1, 'reference' => 2 ) );
			$result = $this->Site->greaterThanIfNotZero( array( 'value' => 1 ), 'reference' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation2RulesComparisonBehavior::compareDates
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

			$data = array( 'from' => '2012-01-01', 'to' => '2012-01-02' );
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

			$data = array( 'from' => '2012-01-02', 'to' => '2012-01-01' );
			$this->Site->create( $data );
			$result = $this->Site->compareDates( array( 'from' => $data['from'] ), 'to', '<=' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'from' => '2012-01-01', 'to' => '2012-01-01' );
			$this->Site->create( $data );
			$result = $this->Site->compareDates( array( 'from' => $data['from'] ), 'to', '==' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation2RulesComparisonBehavior::foo
		 *
		 * @return void
		 */
		public function testFoo() {
			$result = $this->Site->foo( null, null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->foo( array(), null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'Site' => array( 'value' => null, 'othervalue' => null ) ) );
			$result = $this->Site->foo( array( 'value' => null ), 'othervalue' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'Site' => array( 'value' => true, 'othervalue' => false ) ) );
			$result = $this->Site->foo( array( 'value' => true ), 'othervalue' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'Site' => array( 'value' => null, 'othervalue' => 5 ) ) );
			$result = $this->Site->foo( array( 'value' => null ), 'othervalue' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->Site->create( array( 'Site' => array( 'value' => false, 'othervalue' => null ) ) );
			$result = $this->Site->foo( array( 'value' => false ), 'othervalue' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Validation2RulesComparisonBehavior::checkUnique
		 *
		 * @return void
		 */
		public function testCheckUnique() {
			$result = $this->Site->checkUnique( null, null, null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'name' => 'CakePHP', 'birthday' => null );
			$this->Site->create( $data );

			$result = $this->Site->checkUnique( array( 'name' => $data['name'] ), null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->checkUnique( array( 'name' => $data['name'] ), array( 'name', 'birthday' ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$data = array( 'id' => 1, 'name' => 'CakePHP', 'birthday' => null );
			$this->Site->create( $data );

			$result = $this->Site->checkUnique( array( 'name' => $data['name'] ), array( 'name', 'birthday' ) );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Site->checkUnique( array( 'name' => $data['name'] ), array( 'name', 'user_id' ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>