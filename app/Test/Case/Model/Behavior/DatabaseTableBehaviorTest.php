<?php
	/**
	 * Code source de la classe DatabaseTableBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DatabaseTableBehavior', 'Model/Behavior' );

	/**
	 * Classe DatabaseTableBehaviorTest.
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class DatabaseTableBehaviorTest extends CakeTestCase
	{
		/**
		 * Modèle Apple utilisé par ce test.
		 *
		 * @var Model
		 */
		public $Apple = null;

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
			$this->Apple = ClassRegistry::init( 'Apple' );
			$this->Apple->Behaviors->attach( 'DatabaseTable' );

			$this->Apple->bindModel(
				array(
					'belongsTo' => array(
						'Parentapple' => array(
							'className' => 'Apple'
						)
					),
					'hasOne' => array(
						'Childapple' => array(
							'className' => 'Apple'
						)
					),
				),
				false
			);
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			unset( $this->Apple );
			parent::tearDown();
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::fields()
		 *
		 * @return void
		 */
		public function testFields() {
			$result = $this->Apple->fields();
			$expected = array (
				'Apple.id',
				'Apple.apple_id',
				'Apple.color',
				'Apple.name',
				'Apple.created',
				'Apple.date',
				'Apple.modified',
				'Apple.mytime',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::sq()
		 *
		 * @return void
		 */
		public function testSq() {
			$result = $this->Apple->sq(
				array(
					'fields' => array( 'Apple.id' ),
					'conditions' => array(
						'Apple.modified >' => '2012-10-31',
						'Apple.color' => 'red'
					),
					'joins' => array(
						$this->Apple->join( 'Parentapple' )
					),
					'limit' => 3
				)
			);
			$expected = 'SELECT "Apple"."id" AS "Apple__id" FROM "public"."apples" AS "Apple" LEFT JOIN "public"."apples" AS "Parentapple" ON ("Apple"."parentapple_id" = "Parentapple"."id")  WHERE "Apple"."modified" > \'2012-10-31\' AND "Apple"."color" = \'red\'    LIMIT 3';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::join()
		 *
		 * @return void
		 */
		public function testJoin() {
			$result = array(
				$this->Apple->join( 'Parentapple' ),
				$this->Apple->join( 'Childapple' ),
			);
			$expected = array (
				array (
					'table' => '"public"."apples"',
					'alias' => 'Parentapple',
					'type' => 'LEFT',
					'conditions' => '"Apple"."parentapple_id" = "Parentapple"."id"',
				),
				array (
					'table' => '"public"."apples"',
					'alias' => 'Childapple',
					'type' => 'LEFT',
					'conditions' => '"Childapple"."apple_id" = "Apple"."id"',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DatabaseTableBehavior::sqLatest()
		 *
		 * @return void
		 */
		public function testSqLatest() {
			$result = $this->Apple->sqLatest(
				'Parentapple',
				'modified'
			);
			$expected = '( "Parentapple"."id" IS NULL OR "Parentapple"."id" IN ( SELECT "parentapples"."id" AS "parentapples__id" FROM "public"."apples" AS "parentapples"   WHERE "Apple"."parentapple_id" = "parentapples"."id"   ORDER BY "parentapples"."modified" DESC  LIMIT 1 ) )';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Apple->sqLatest(
				'Parentapple',
				'modified',
				array( 'Parentapple.color' => 'red' ),
				false
			);
			$expected = 'SELECT "parentapples"."id" AS "parentapples__id" FROM "public"."apples" AS "parentapples"   WHERE "Apple"."parentapple_id" = "parentapples"."id" AND "parentapples"."color" = \'red\'   ORDER BY "parentapples"."modified" DESC  LIMIT 1';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>