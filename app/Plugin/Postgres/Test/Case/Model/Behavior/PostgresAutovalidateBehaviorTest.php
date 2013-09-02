<?php
	/**
	 * Code source de la classe PostgresAutovalidateBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PostgresAutovalidateBehaviorTest ...
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Behavior
	 */
	class PostgresAutovalidateBehaviorTest extends CakeTestCase
	{
		/**
		 *
		 * @var AppModel
		 */
		public $Site = null;

		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Postgres.PostgresSite',
		);

		/**
		 * Préparation du test.
		 *
		 * INFO: ne pas utiliser parent::setUp();
		 *
		 * @return void
		 */
		public function setUp() {
			$this->Site = ClassRegistry::init(
				array(
					'class' => 'Postgres.PostgresSite',
					'alias' => 'Site',
					'table' => 'postgres_sites'
				)
			);
			$this->Site->Behaviors->attach( 'Postgres.PostgresAutovalidate' );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			unset( $this->Site );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresAutovalidateBehavior::setup()
		 *
		 * @return void
		 */
		public function testSetup() {
			$result = Hash::get( $this->Site->validate, 'status.inList' );
			$expected = array(
				'rule' => array( 'inList', array( 'spam', 'ham' ) ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = Hash::get( $this->Site->validate, 'price.inclusiveRange' );
			$expected = array(
				'rule' => array( 'inclusiveRange', '0', '999' ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = Hash::get( $this->Site->validate, 'price.range' );
			$expected = array(
				'rule' => array( 'range', '-1', '1000' ),
				'message' => null,
				'required' => null,
				'allowEmpty' => true,
				'on' => null
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>