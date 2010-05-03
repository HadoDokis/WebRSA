<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','AppModel');

	class AppModelTest extends AppModel
	{
		public function testQueryFields( $queryData = array() ) {
			return parent::queryFields( $queryData );
		}
	}

	/*class Item extends AppModel
	{
		public $name = 'Item';
        public $useTable = 'items';
		public $hasOne = array(
            'Partitem' => array(
                'classname' => 'Partitem',
                'foreignKey' => 'item_id',
            )
		);
	}

	class Partitem extends AppModel
	{
		public $name = 'Partitem';
        public $useTable = 'partitems';
		public $belongsTo = array('Item');
	}*/

	class AppModelTestCase extends CakeAppModelTestCase
	{
		public $fixtures = array(
			'app.item',
			'app.partitem'
		);

		/**
		* Exécuté avant chaque test.
		*/
		public function startTest() {
			$this->Item =& new AppModelTest(array('table'=>'items', 'name'=>'Item', 'ds'=>'test_suite'));
			$this->Partitem =& new AppModelTest(array('table'=>'partitems', 'name'=>'Partitem', 'ds'=>'test_suite'));
			$this->Item->virtualFields = array(
				'fullname' => array(
					'type'		=> 'string',
					'postgres'	=> '( "%s"."firstname" || \' \' || "%s"."lastname" )' // FIXME: Postgres only
				),
				'nbpartitems' => array(
					'type'		=> 'integer',
					'query'		=> '( SELECT COUNT( "partitems"."id" ) FROM "partitems" WHERE "partitems"."item_id" = "Item"."id" )'
				)
			);
			$this->Item->virtualFields = $this->Item->initVirtualFields( $this->Item->virtualFields );
			$this->Item->bindModel( array( 'hasOne' => array( 'Partitem' ) ), false );
			$this->Item->displayField = 'fullname';
			$this->Partitem->bindModel( array( 'belongsTo' => array( 'Item' ) ), false );
		}

		/**
		* Exécuté après chaque test.
		*/
		function tearDown() {
			ClassRegistry::flush();
			unset( $this->Item );
		}

		function testQueryFields() {
			$result = $this->Item->testQueryFields( array( 'recursive' => -1 ) );
			$expected = array(
				0 => "Item.id",
				1 => "Item.firstname",
				2 => "Item.lastname",
				3 => "Item.name_a",
				4 => "Item.name_b",
				5 => "Item.version_a",
				6 => "Item.version_n",
				7 => "Item.description_a",
				8 => "Item.description_b",
				9 => "Item.modifiable_a",
				10 => "Item.modifiable_b",
				11 => "Item.date_a",
				12 => "Item.date_b",
				13 => "Item.tel",
				14 => "Item.fax",
				15 => "Item.category_id",
				16 => "Item.foo",
				17 => "Item.bar",
				18 => "Item.montant"
			);
 			$this->assertEqual($result, $expected);
			// FIXME: virtualFields ?
		}

		/**
		* Fonction de test de différents find
		* sur le model Item
		*/
		function testFindItem() {
			$result = $this->Item->findById( 1, null, null, -1 );
			$expected = array(
				"Item" => array(
					"id" => 1,
					"firstname" => "Firstname n°1",
					"lastname" => "Lastname n°1",
					"name_a" => "name_a",
					"name_b" => "name_b",
					"version_a" => 1,
					"version_n" => 1,
					"description_a" => "description_a",
					"description_b" => "description_b",
					"modifiable_a" => 1,
					"modifiable_b" => 1,
					"date_a" => "2010-03-17",
					"date_b" => "2010-03-17",
					"tel" => "0101010101",
					"fax" => "0101010101",
					"category_id" => 12,
					"foo" => "f",
					"bar" => "",
					"montant" => "666.66",
					"fullname" => "Firstname n°1 Lastname n°1",
					"nbpartitems" => 1
				)
			);
 			$this->assertEqual($result, $expected);

			// -----------------------------------------------------------------

			$result = $this->Item->find('first', array(
				'recursive' => -1
			));
			$expected = array(
				"Item" => array(
					"id" => 1,
					"firstname" => "Firstname n°1",
					"lastname" => "Lastname n°1",
					"name_a" => "name_a",
					"name_b" => "name_b",
					"version_a" => 1,
					"version_n" => 1,
					"description_a" => "description_a",
					"description_b" => "description_b",
					"modifiable_a" => 1,
					"modifiable_b" => 1,
					"date_a" => "2010-03-17",
					"date_b" => "2010-03-17",
					"tel" => "0101010101",
					"fax" => "0101010101",
					"category_id" => 12,
					"foo" => "f",
					"bar" => "",
					"montant" => "666.66",
					"fullname" => "Firstname n°1 Lastname n°1",
					"nbpartitems" => 1
				)
			);
 			$this->assertEqual($result, $expected);

			// -----------------------------------------------------------------

			$result = $this->Item->find(
				'all',
				array(
					'conditions' => array('Item.id' => 1),
					'recursive' => -1
				)
			);
			$expected = array(
				0 => array(
					"Item" => array(
						"id" => 1,
						"firstname" => "Firstname n°1",
						"lastname" => "Lastname n°1",
						"name_a" => "name_a",
						"name_b" => "name_b",
						"version_a" => 1,
						"version_n" => 1,
						"description_a" => "description_a",
						"description_b" => "description_b",
						"modifiable_a" => 1,
						"modifiable_b" => 1,
						"date_a" => "2010-03-17",
						"date_b" => "2010-03-17",
						"tel" => "0101010101",
						"fax" => "0101010101",
						"category_id" => 12,
						"foo" => "f",
						"bar" => "",
						"montant" => "666.66",
						"fullname" => "Firstname n°1 Lastname n°1",
						"nbpartitems" => 1
					)
				)
			);
 			$this->assertEqual($result, $expected);

			// -----------------------------------------------------------------

			$result = $this->Item->find('list',array(
				'conditions' => array('Item.id' => 1),
				'recursive' => -1
			));
			$expected= array( 1 => 'Firstname n°1 Lastname n°1' );
			$this->assertEqual($result, $expected);

			// -----------------------------------------------------------------

			// Couverture du code ligne 167
			$expected = array( 'Item' => array( 'firstname' => 'Firstname n°1' ) );
			$result = $this->Item->read( 'firstname', 1 );
			$this->assertEqual($result, $expected);

			// -----------------------------------------------------------------

			// Couverture du code ligne 196
			$expected = array( 'Item' => array( 'firstname' => 'Firstname n°1' ) );
			$result = $this->Item->find( 'first');
			//$this->assertEqual($result, $expected);
		}

		/**
		* Fonction de test de différents find
		* sur le model Partitem
		*/
		function testFindPartitem() {
			$result = $this->Partitem->Item->find('first', array(
				'recursive' => 0
			));
			$expected = array(
				"Item" => array(
					"id" => 1,
					"firstname" => "Firstname n°1",
					"lastname" => "Lastname n°1",
					"name_a" => "name_a",
					"name_b" => "name_b",
					"version_a" => 1,
					"version_n" => 1,
					"description_a" => "description_a",
					"description_b" => "description_b",
					"modifiable_a" => 1,
					"modifiable_b" => 1,
					"date_a" => "2010-03-17",
					"date_b" => "2010-03-17",
					"tel" => "0101010101",
					"fax" => "0101010101",
					"category_id" => 12,
					"foo" => "f",
					"bar" => "",
					"montant" => "666.66",
					"fullname" => "Firstname n°1 Lastname n°1",
					"nbpartitems" => 1
				),
				"Partitem" => array(
					"id" => 1,
					"nbpart" => 1,
					"name" => "nom de la partie 1",
					"item_id" => 1
				)
			);
 			$this->assertEqual($result, $expected);

			// -----------------------------------------------------------------

			$result = $this->Partitem->Item->find('all', array(
				'conditions' => array('Item.id' => 1),
				'recursive' => -1
				));
			$expected = array(
				0 => array(
					"Item" => array(
						"id" => 1,
						"firstname" => "Firstname n°1",
						"lastname" => "Lastname n°1",
						"name_a" => "name_a",
						"name_b" => "name_b",
						"version_a" => 1,
						"version_n" => 1,
						"description_a" => "description_a",
						"description_b" => "description_b",
						"modifiable_a" => 1,
						"modifiable_b" => 1,
						"date_a" => "2010-03-17",
						"date_b" => "2010-03-17",
						"tel" => "0101010101",
						"fax" => "0101010101",
						"category_id" => 12,
						"foo" => "f",
						"bar" => "",
						"montant" => "666.66",
						"fullname" => "Firstname n°1 Lastname n°1",
						"nbpartitems" => 1
					)
				)
			);
 			$this->assertEqual($result, $expected);

			// -----------------------------------------------------------------

			$result = $this->Partitem->Item->find('list',array(
				'conditions' => array('Item.id' => 1),
				'recursive' => -1
			));
			$expected= array( 1 => 'Firstname n°1 Lastname n°1');
			$this->assertEqual($result, $expected);
		}

		/**
		*
		*/

		function testFindWithOrder()
		{
			$result = $this->Item->find('all',array(
				'recursive' => -1,
				'order' => 'Item.id DESC'
			));
			$expected = array(
				0 => array(
					"Item" => array(
						"id" => 3,
						"firstname" => "Firstname n°3",
						"lastname" => "Lastname n°3",
						"name_a" => "name_e",
						"name_b" => "name_f",
						"version_a" => 3,
						"version_n" => 3,
						"description_a" => "description_e",
						"description_b" => "description_f",
						"modifiable_a" => 0,
						"modifiable_b" => 1,
						"date_a" => "2010-03-12",
						"date_b" => "2010-03-12",
						"tel" => "0303030303",
						"fax" => "0303030303",
						"category_id" => 736,
						"foo" => "o",
						"bar" => "",
						"montant" => "867.3",
						"fullname" => "Firstname n°3 Lastname n°3",
						"nbpartitems" => 0
					)
				),
				1 => array(
					"Item" => array(
						"id" => 2,
						"firstname" => "Firstname n°2",
						"lastname" => "Lastname n°2",
						"name_a" => "name_c",
						"name_b" => "name_d",
						"version_a" => 2,
						"version_n" => 2,
						"description_a" => "description_c",
						"description_b" => "description_d",
						"modifiable_a" => 1,
						"modifiable_b" => 0,
						"date_a" => "2010-03-23",
						"date_b" => "2010-03-23",
						"tel" => "0202020202",
						"fax" => "0202020202",
						"category_id" => 45,
						"foo" => "o",
						"bar" => "",
						"montant" => "123",
						"fullname" => "Firstname n°2 Lastname n°2",
						"nbpartitems" => 2
					)
				),
				2 => array(
					"Item" => array(
						"id" => 1,
						"firstname" => "Firstname n°1",
						"lastname" => "Lastname n°1",
						"name_a" => "name_a",
						"name_b" => "name_b",
						"version_a" => 1,
						"version_n" => 1,
						"description_a" => "description_a",
						"description_b" => "description_b",
						"modifiable_a" => 1,
						"modifiable_b" => 1,
						"date_a" => "2010-03-17",
						"date_b" => "2010-03-17",
						"tel" => "0101010101",
						"fax" => "0101010101",
						"category_id" => 12,
						"foo" => "f",
						"bar" => "",
						"montant" => "666.66",
						"fullname" => "Firstname n°1 Lastname n°1",
						"nbpartitems" => 1
					)
				)
			);
			$this->assertEqual($result,$expected);
		}

		/**
		*
		*/

		function testFindWithCondition()
		{
			$result = $this->Item->find('all',array(
				'conditions' => array('Item.nbpartitems' => 0),
				'recursive' => -1
			));
			$expected = array(
				0 => array(
					"Item" => array(
						"id" => 3,
						"firstname" => "Firstname n°3",
						"lastname" => "Lastname n°3",
						"name_a" => "name_e",
						"name_b" => "name_f",
						"version_a" => 3,
						"version_n" => 3,
						"description_a" => "description_e",
						"description_b" => "description_f",
						"modifiable_a" => 0,
						"modifiable_b" => 1,
						"date_a" => "2010-03-12",
						"date_b" => "2010-03-12",
						"tel" => "0303030303",
						"fax" => "0303030303",
						"category_id" => 736,
						"foo" => "o",
						"bar" => "",
						"montant" => "867.3",
						"fullname" => "Firstname n°3 Lastname n°3",
						"nbpartitems" => 0
					)
				)
			);
			$this->assertEqual($result,$expected);
		}

		/**
		*
		*/
		function testColumnType() {
			$this->assertEqual($this->Item->getColumnType('id',true), 'integer');
			$this->assertEqual($this->Item->getColumnType('firstname',true), 'string');
			$this->assertEqual($this->Item->getColumnType('description_a',true), 'text');
			$this->assertNull($this->Item->getColumnType('unknown',true));

			/// FIXME : problème lors de la copie de la fonction originelle à réparer
//  			$this->assertEqual($this->Item->getColumnType('Item.date_a'), 'date');
//  			$this->assertEqual($this->Item->getColumnType('Partitem.id'), 'integer');
		}

		/**
		*
		*/

		public function testVirtualFieldsErrors() {
			$savedVirtualFields = $this->Item->virtualFields;

			// Couverture ligne 83
			$this->Item->virtualFields = array(
				'fullname' => array(
					'type'		=> 'string'
				)
			);
			$this->Item->initVirtualFields( $this->Item->virtualFields );
			$this->assertError( 'No query available for field fullname' );

			// Couverture ligne 86
			$this->Item->virtualFields = array(
				'fullname' => array(
					'postgres'	=> '( "%s"."firstname" || \' \' || "%s"."lastname" )'
				)
			);
			$this->Item->initVirtualFields( $this->Item->virtualFields );
			$this->assertError( 'No type specified for field fullname' );

			// INFO: couverture ligne 170
			$this->Item->virtualFields = $savedVirtualFields;
			unset( $this->Item->virtualFields['fullname']['regex'] );
			$this->Item->find( 'first' );
			$this->assertError( '...' );

			// INFO: couverture ligne 170
			$this->Item->virtualFields = $savedVirtualFields;
			unset( $this->Item->virtualFields['fullname']['alias'] );
			$this->Item->find( 'first' );
			$this->assertError( '...' );
		}

		/**
		*
		*/
		function testSchema() {

 			$result = $this->Item->schema(false, true);
 			$columns = array('id', 'firstname', 'lastname', 'name_a', 'name_b', 'version_a', 'version_n', 'description_a', 'description_b', 'modifiable_a', 'modifiable_b', 'date_a', 'date_b', 'tel', 'fax', 'category_id', 'foo', 'bar', 'montant', 'fullname', 'nbpartitems');

			// -----------------------------------------------------------------

			$types = array('integer', 'string', 'string', 'string', 'string', 'integer', 'integer', 'text', 'text', 'boolean', 'boolean', 'date', 'date', 'string', 'string', 'integer', 'string', 'string', 'float', 'string', 'integer');
 			$this->assertEqual(Set::extract(array_values($result), '{n}.type'), $types);

			// -----------------------------------------------------------------

 			$result = $this->Item->schema('lastname',false);
 			$this->assertEqual($result['type'], 'string');

			// -----------------------------------------------------------------

 			$result = $this->Item->schema('lastname',true);
 			$this->assertEqual($result['type'], 'string');

			// -----------------------------------------------------------------

			$this->assertNull($this->Item->schema('fullname',false));

			// -----------------------------------------------------------------

 			$result = $this->Item->schema('fullname',true);
 			$this->assertEqual($result['type'], 'string');
 			$this->assertTrue($result['virtual']);

			// -----------------------------------------------------------------

 			$this->assertNull($this->Item->schema('foobar'));

			// -----------------------------------------------------------------
	
			$this->assertEqual($this->Item->getColumnTypes(true), array_combine($columns, $types));
		}

		/**
		*
		*/
		public function testHasField() {

			$this->assertTrue($this->Item->hasField("foo",false));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->hasField("foo",true));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->hasField("nbpartitems",true));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->hasField("nbpartitems",false));
		}

		/**
		*
		*/
		public function testBind() {

			/// FIXME : fonction qui testera la fonction privée __generateAssociation
		}

		/**
		*
		*/
		public function testAlphaNumeric() {

			$this->assertTrue($this->Item->alphaNumeric("abc123"));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->alphaNumeric("azert%"));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->alphaNumeric("123#456"));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->alphaNumeric(array("123#45" => "azerty")));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->alphaNumeric(array("qwerty" => "bhg$67")));
		}

		/**
		*
		*/
		public function testFutureDate() {

			$this->assertFalse($this->Item->futureDate(array('date' => '2000-10-21')));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->futureDate(array('date' => date( 'Y-m-d', strtotime( '+1 year' ) ))));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->futureDate(array('date' => date('Y-m-d'))));
		}

		/**
		*
		*/
		public function testInteger() {

			$this->assertFalse($this->Item->integer(123));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->integer("456"));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->integer(array('nombre' => '123')));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->integer(array('nombre' => 456 )));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->integer(array('nombre' => 789.10)));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->integer(array('lettres' => "abc" )));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->integer(array('alphanum' => "123abc")));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->integer(array('nb1' => "123", 'nb2' => "456", 'nb3' => "789")));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->integer(array('nb1' => "123", 'err' => "qwerty", 'nb2' => "789")));
		}

		/**
		*
		*/
		public function testPhoneFr() {

			$this->assertTrue($this->Item->phoneFr(array('phonenb' => '01.23.45.67.89')));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->phoneFr(array('phonenb' => '98 76 54 32 10')));

			// -----------------------------------------------------------------

			$this->assertTrue($this->Item->phoneFr(array('phonenb' => '5678901234')));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->phoneFr(array('phonenb' => '12345678901234567890')));

			// -----------------------------------------------------------------

			$this->assertFalse($this->Item->phoneFr(array('phonenb' => 'azertyuiop')));
		}

		/**
		*
		*/
		public function testAllEmpty() {
			$this->Item->data = array(
				'Item' => array(
					'firstname' => '',
					'lastname' => ''
				)
			);
			$this->assertTrue( $this->Item->allEmpty( array( 'firstname' => '' ), 'lastname' ) );

			// -----------------------------------------------------------------

			$this->Item->data = array(
				'Item' => array(
					'firstname' => 'fn',
					'lastname' => ''
				)
			);
			$this->assertFalse( $this->Item->allEmpty( array( 'firstname' => 'fn' ), 'lastname' ) );
		}

		/**
		*
		*/
		public function testUnbindModelAll() {
			$this->Item->unbindModelAll();
			$this->assertEqual( array(), $this->Item->hasOne );
			$this->Item->bindModel( array( 'hasOne' => array( 'Partitem' ) ), false );
		}

		/**
		* FIXME: fieldList change-t'il quelque chose
		*/
		public function testSaveAll() {

			$this->Item->validate = array(
				'firstname' => array(
					'rule' => 'notEmpty'
				)
			);

			$data = array(
				"Item" => array(
					"id" => 4,
					"firstname" => "Firstname n°4",
					"lastname" => "Lastname n°4",
					"name_a" => "name_g",
					"name_b" => "name_h",
					"version_a" => 4,
					"version_n" => 4,
					"description_a" => "description_g",
					"description_b" => "description_h",
					"modifiable_a" => 4,
					"modifiable_b" => 4,
					"date_a" => "2010-03-25",
					"date_b" => "2010-03-25",
					"tel" => "0404040404",
					"fax" => "0404040404",
					"category_id" => 3,
					"foo" => "",
					"bar" => "b",
					"montant" => "123.45"
				)
			);
			$this->assertTrue($this->Item->saveAll($data,array('atomic'=>true, 'validate'=>true)));

			// -----------------------------------------------------------------

			$data = array(
				"id" => 4,
				"firstname" => "",
				"lastname" => "Lastname n°4",
				"name_a" => "name_g",
				"name_b" => "name_h",
				"version_a" => 4,
				"version_n" => 4,
				"description_a" => "description_g",
				"description_b" => "description_h",
				"modifiable_a" => 4,
				"modifiable_b" => 4,
				"date_a" => "2010-03-25",
				"date_b" => "2010-03-25",
				"tel" => "0404040404",
				"fax" => "0404040404",
				"category_id" => 3,
				"foo" => "",
				"bar" => "b",
				"montant" => "123.45"
			);
			$this->assertFalse($this->Item->saveAll($data,array('atomic'=>true, 'validate'=>true)));

			// -----------------------------------------------------------------

			$data = array(
				"id" => 4,
				"firstname" => "Firstname n°4",
				"lastname" => "Lastname n°4",
				"name_a" => "name_g",
				"name_b" => "name_h",
				"version_a" => 4,
				"version_n" => 4,
				"description_a" => "description_g",
				"description_b" => "description_h",
				"modifiable_a" => 4,
				"modifiable_b" => 4,
				"date_a" => "2010-03-25",
				"date_b" => "2010-03-25",
				"tel" => "0404040404",
				"fax" => "0404040404",
				"category_id" => 3,
				"foo" => "",
				"bar" => "b",
				"montant" => "123.45"
			);
			$this->assertTrue($this->Item->saveAll($data,array('atomic'=>false, 'validate'=>true)));

			// -----------------------------------------------------------------

			$data = array(
				"id" => 4,
				"firstname" => "",
				"lastname" => "Lastname n°4",
				"name_a" => "name_g",
				"name_b" => "name_h",
				"version_a" => 4,
				"version_n" => 4,
				"description_a" => "description_g",
				"description_b" => "description_h",
				"modifiable_a" => 4,
				"modifiable_b" => 4,
				"date_a" => "2010-03-25",
				"date_b" => "2010-03-25",
				"tel" => "0404040404",
				"fax" => "0404040404",
				"category_id" => 3,
				"foo" => "",
				"bar" => "b",
				"montant" => "123.45"
			);
			$this->assertFalse($this->Item->saveAll($data,array('atomic'=>false, 'validate'=>true)));

		}
	}
?>
