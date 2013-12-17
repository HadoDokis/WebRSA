<?php
	/**
	 * Code source de la classe LinkedRecordsBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'LinkedRecordsBehavior', 'Model/Behavior' );
	require_once CORE_TEST_CASES . DS . 'Model' . DS . 'models.php';

	/**
	 * La classe LinkedRecordsBehaviorTest réalise les tests unitaires de la
	 * classe LinkedRecordsBehavior.
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class LinkedRecordsBehaviorTest extends CakeTestCase
	{
		/**
		 * Modèle User utilisé par ce test.
		 *
		 * @var Model
		 */
		public $User = null;

		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.User',
			'core.Article',
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->User = ClassRegistry::init( 'User' );
			$this->User->Behaviors->attach( 'DatabaseTable' );
			$this->User->bindModel( array( 'hasMany' => array( 'Article' ) ), false );

			$this->User->Article->Behaviors->attach( 'DatabaseTable' );
			$this->User->Behaviors->attach( 'LinkedRecords' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->User );
			parent::tearDown();
		}

		/**
		 * Test de la méthode LinkedRecordsBehavior::linkedRecordVirtualFieldName()
		 */
		public function testLinkedRecordVirtualFieldName() {
			$result = $this->User->linkedRecordVirtualFieldName( 'Article' );
			$expected = 'has_article';
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode LinkedRecordsBehavior::linkedRecordVirtualField()
		 */
		public function testLinkedRecordVirtualField() {
			// Sans argument particulier
			$result = $this->User->linkedRecordVirtualField( 'Article' );
			$expected = 'EXISTS( SELECT "articles"."id" AS "articles__id" FROM "articles" AS "articles"   WHERE "articles"."user_id" = "User"."id"    )';
			$this->assertEquals( $expected, $result );

			// Avec une condition supplémentaire
			$querydata = array(
				'conditions' => array( 'Article.name' => 'article1' )
			);
			$result = $this->User->linkedRecordVirtualField( 'Article', $querydata );
			$expected = 'EXISTS( SELECT "articles"."id" AS "articles__id" FROM "articles" AS "articles"   WHERE "articles"."name" = \'article1\' AND "articles"."user_id" = "User"."id"    )';
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode LinkedRecordsBehavior::linkedRecordVirtualField()
		 * avec des jointures.
		 */
		public function testLinkedRecordVirtualFieldWithJoins() {
			$querydata = array(
				'contain' => false,
				'joins' => array(
					$this->User->Article->join( 'Comment', array( 'type' => 'INNER' ) )
				),
				'conditions' => array( 'Article.name' => 'article1' ),
			);
			$result = $this->User->linkedRecordVirtualField( 'Article', $querydata );
			$expected = 'EXISTS( SELECT "articles"."id" AS "articles__id" FROM "articles" AS "articles" INNER JOIN "public"."comments" AS "comments" ON ("comments"."article_id" = "articles"."id")  WHERE "articles"."name" = \'article1\' AND "articles"."user_id" = "User"."id"    )';
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode LinkedRecordsBehavior::linkedRecordsCompleteQuerydata()
		 */
		public function testLinkedRecordsCompleteQuerydata() {
			$querydata = array(
				'fields' => array(
					'User.id',
					'User.name',
				),
				'conditions' => array(
					'User.id >' => 10
				),
				'contain' => false,
				'order' => array( 'User.id DESC' )
			);
			$result = $this->User->linkedRecordsCompleteQuerydata( $querydata, 'Article' );
			$expected = array(
				'fields' => array(
					'User.id',
					'User.name',
					'( EXISTS( SELECT "articles"."id" AS "articles__id" FROM "articles" AS "articles"   WHERE "articles"."user_id" = "User"."id"    ) ) AS "User__has_article"'
				),
				'conditions' => array(
					'User.id >' => 10
				),
				'contain' => false,
				'order' => array( 'User.id DESC' )
			);
			$this->assertEquals( $expected, $result );
		}

		/**
		 * Test de la méthode LinkedRecordsBehavior::linkedRecordsLoadVirtualFields()
		 */
		public function testLinkedRecordsLoadVirtualFields() {
			// 1. Pour un modèle lié, sans rien de particulier
			$this->User->virtualFields = array();
			$this->User->linkedRecordsLoadVirtualFields( 'Article' );
			$result = $this->User->virtualFields;
			$expected = array(
				'has_article' => 'EXISTS( SELECT "articles"."id" AS "articles__id" FROM "articles" AS "articles"   WHERE "articles"."user_id" = "User"."id"    )'
			);
			$this->assertEquals( $expected, $result );

			// 2. Pour un modèle lié, avec une condition et une jointure
			$this->User->virtualFields = array();
			$querydata = array(
				'contain' => false,
				'joins' => array(
					$this->User->Article->join( 'Comment', array( 'type' => 'INNER' ) )
				),
				'conditions' => array( 'Article.name' => 'article1' ),
			);
			$this->User->linkedRecordsLoadVirtualFields( array( 'Article' => $querydata ) );
			$result = $this->User->virtualFields;
			$expected = array(
				'has_article' => 'EXISTS( SELECT "articles"."id" AS "articles__id" FROM "articles" AS "articles" INNER JOIN "public"."comments" AS "comments" ON ("comments"."article_id" = "articles"."id")  WHERE "articles"."name" = \'article1\' AND "articles"."user_id" = "User"."id"    )'
			);
			$this->assertEquals( $expected, $result );
		}
	}
?>