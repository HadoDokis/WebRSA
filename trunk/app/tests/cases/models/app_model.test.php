<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','AppModel');

	// Classe utilisée pour étendre la classe Appmodel à tester afin de pouvoir appeler les fonctions protected
	class AppModelTest extends AppModel
	{
		// fonction servant à appeler la fonction protected de sa classe mère
		public function testQueryFields( $queryData = array() ) {
			return parent::queryFields( $queryData );
		}
	}

	class AppModelTestCase extends CakeAppModelTestCase
	{

		/**
		* Exécuté avant chaque test.
		*/
		public function startTest() {
			// initialisation des modèles associés au modèle de test
			$this->Item =& new AppModelTest(array('table'=>'items', 'name'=>'Item', 'ds'=>'test_suite'));
			$this->Partitem =& new AppModelTest(array('table'=>'partitems', 'name'=>'Partitem', 'ds'=>'test_suite'));
			// initialisation des champs virtuels
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
			unset( $this->Partitem );
		}

		/**
		* Test de la fonction queryFields appelée via une autre classe car elle est en protected
		*/
		function testQueryFields() {
			// récupération des champs de la table items
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
			// test avec la fonction findById sur l'item d'id 1 uniquement
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

			// test sur la fonction find first
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

			// test sur la fonction find all avec comme condition Item.id=1
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

			// test sur la fonction find list
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

		/*Fonction de test de différents find
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
		* fonction de test du find en lui précisant un ordre
		*/
		function testFindWithOrder()
		{
			// test sur la fonction find all en les triant par id DESC
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
						"nbpartitems" => 3
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
		* test sur la fonction find avec condition
		*/
		function testFindWithCondition()
		{
			// test sur la fonction find all avec commme condition que l'item ai 3 parties
			$result = $this->Item->find('all',array(
				'conditions' => array('Item.nbpartitems' => 3),
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
						"nbpartitems" => 3
					)
				)
			);
			$this->assertEqual($result,$expected);

			// -----------------------------------------------------------------

			// test sur la fonction find all avec commme condition que l'item ai l'id 4 (n'existe pas)
			$result = $this->Item->find('all',array(
				'conditions' => array('Item.id' => '3876'),
				'recursive' => -1
			));
			$expected=array();
			$this->assertEqual($expected,$result);

		}

		/**
		* fonction de test sur le type des champs dans la bdd
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
		* fonction de test sur les erreurs des champs virtuels
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
		* fonction de test des schemas
		*/
		function testSchema() {

 			$result = $this->Item->schema(false, true);
 			$columns = array('id', 'firstname', 'lastname', 'name_a', 'name_b', 'version_a', 'version_n', 'description_a', 'description_b', 'modifiable_a', 'modifiable_b', 'date_a', 'date_b', 'tel', 'fax', 'category_id', 'foo', 'bar', 'montant', 'fullname', 'nbpartitems');

			// -----------------------------------------------------------------

			//test pour les types des champs du schéma de la table items
			$types = array('integer', 'string', 'string', 'string', 'string', 'integer', 'integer', 'text', 'text', 'boolean', 'boolean', 'date', 'date', 'string', 'string', 'integer', 'string', 'string', 'float', 'string', 'integer');
 			$this->assertEqual(Set::extract(array_values($result), '{n}.type'), $types);

			// -----------------------------------------------------------------

			// test pour le type du champ lastname de la table items avec la variable champs virtuel à faux
 			$result = $this->Item->schema('lastname',false);
 			$this->assertEqual($result['type'], 'string');

			// -----------------------------------------------------------------

			// test pour le type du champ lastname de la table items avec la variable champ virtuel à vrai
 			$result = $this->Item->schema('lastname',true);
 			$this->assertEqual($result['type'], 'string');

			// -----------------------------------------------------------------

			// test pour le champs virtuel fullname de la table items avec la variable champ virtuel à faux
			$this->assertNull($this->Item->schema('fullname',false));

			// -----------------------------------------------------------------

			// test pour le champ fullname de la table items avec la variable champ virtuel à vrai
			// et vérification que celui-ci est bien un champs virtuel
 			$result = $this->Item->schema('fullname',true);
 			$this->assertEqual($result['type'], 'string');
 			$this->assertTrue($result['virtual']);

			// -----------------------------------------------------------------

			// test pour vérifier que le champ foobar n'existe pas dans la table items
 			$this->assertNull($this->Item->schema('foobar'));

			// -----------------------------------------------------------------

			// vérification pour la totalité des colonnes et de leur type associé
			$this->assertEqual($this->Item->getColumnTypes(true), array_combine($columns, $types));
		}

		/**
		* Test pour la fonction hasField qui vérifie si un champs existe ou non
		*/
		public function testHasField() {
			// test sur le champ foo sans chercher dans les champs virtuels
			$this->assertTrue($this->Item->hasField("foo",false));

			// -----------------------------------------------------------------

			// test sur le champ foo en cherchant dans les champs virtuels
			$this->assertTrue($this->Item->hasField("foo",true));

			// -----------------------------------------------------------------

			// test sur le champ nbpartitems en cherchant dans les champs virtuels
			$this->assertTrue($this->Item->hasField("nbpartitems",true));

			// -----------------------------------------------------------------

			// test sur le champ nb partitems sans chercher dans les champs virtuels
			$this->assertFalse($this->Item->hasField("nbpartitems",false));
		}

		/**
		* Test pour le fonction __generateAssociation
		*/
		public function testBind() {

			/// FIXME : fonction qui testera la fonction privée __generateAssociation
		}

		/**
		* Test sur la fonction alphaNumeric
		*/
		public function testAlphaNumeric() {

			// test avec une chaine contenant des lettres et des chiffres
			$this->assertTrue($this->Item->alphaNumeric("abc123"));

			// -----------------------------------------------------------------

			// test pour une chaine contenant des lettres et un caractère spécial
			$this->assertFalse($this->Item->alphaNumeric("azert%"));

			// -----------------------------------------------------------------

			// test pour une chaine contenant des chiffres et un caractère spécial eu milieu
			$this->assertFalse($this->Item->alphaNumeric("123#456"));

			// -----------------------------------------------------------------

			// test sur la valeur d'un tableau avec une chaine de lettres
			$this->assertTrue($this->Item->alphaNumeric(array("123#45" => "azerty")));

			// -----------------------------------------------------------------

			// test sur la valeur d'un tableau avec des lettres, un caractère spécial et des chiffres
			$this->assertFalse($this->Item->alphaNumeric(array("qwerty" => "bhg$67")));
		}

		/**
		* Test sur la fonction futureDate
		*/
		public function testFutureDate() {

			// test avec une date dépassée d'un an
			$this->assertFalse($this->Item->futureDate(array('date' => date( 'Y-m-d', strtotime( '-1 year' ) ))));

			// -----------------------------------------------------------------

			// test avec une date à venir
			$this->assertTrue($this->Item->futureDate(array('date' => date( 'Y-m-d', strtotime( '+1 year' ) ))));

			// -----------------------------------------------------------------

			// test avec la date du jour
			$this->assertTrue($this->Item->futureDate(array('date' => date('Y-m-d'))));
		}

		/**
		* Test pour la fonction integer
		*/
		public function testInteger() {

			// test avec une nombre hors d'un tableau
			$this->assertFalse($this->Item->integer(123));

			// -----------------------------------------------------------------

			// test avec une chaine hors d'un tableau
			$this->assertFalse($this->Item->integer("456"));

			// -----------------------------------------------------------------

			// test avec un nombre entre côte comme valeur dans un tableau
			$this->assertTrue($this->Item->integer(array('nombre' => '123')));

			// -----------------------------------------------------------------

			// test avec un nombre comme valeur dans un tableau
			$this->assertTrue($this->Item->integer(array('nombre' => 456 )));

			// -----------------------------------------------------------------

			// test avec un nombre décimal comme valeur dans un tableau
			$this->assertFalse($this->Item->integer(array('nombre' => 789.10)));

			// -----------------------------------------------------------------

			// test avec des lettres comme valeur dans un tableau
			$this->assertFalse($this->Item->integer(array('lettres' => "abc" )));

			// -----------------------------------------------------------------

			// test avec des caractères alphanumériques dans un tableau
			$this->assertFalse($this->Item->integer(array('alphanum' => "123abc")));

			// -----------------------------------------------------------------

			// test avec plusieurs case dans un tableau dont toutes les valeurs sont des nombres
			$this->assertTrue($this->Item->integer(array('nb1' => "123", 'nb2' => "456", 'nb3' => "789")));

			// -----------------------------------------------------------------

			// test avec plusieurs case dans un tableau dont une des valeurs et une chaine de caractères
			$this->assertFalse($this->Item->integer(array('nb1' => "123", 'err' => "qwerty", 'nb2' => "789")));
		}

		/**
		* Test de la fonction phoneFr
		*/
		public function testPhoneFr() {

			// test avec un numéro dont les nombres sont séparés 2 à 2 par des points
			$this->assertTrue($this->Item->phoneFr(array('phonenb' => '01.23.45.67.89')));

			// -----------------------------------------------------------------

			// test avec un numéro dont les nombres sont séparés 2 à 2 par des espaces
			$this->assertTrue($this->Item->phoneFr(array('phonenb' => '98 76 54 32 10')));

			// -----------------------------------------------------------------

			// test avec un numéro dont les nombres sont collés
			$this->assertTrue($this->Item->phoneFr(array('phonenb' => '5678901234')));

			// -----------------------------------------------------------------

			// test avec un numéro de 20 chiffres et donc inexistant
			$this->assertFalse($this->Item->phoneFr(array('phonenb' => '12345678901234567890')));

			// -----------------------------------------------------------------

			// test avec une chaine de 10 caractères
			$this->assertFalse($this->Item->phoneFr(array('phonenb' => 'azertyuiop')));
		}

		/**
		* Test de la fonction allEmpty
		*/
		public function testAllEmpty() {
			// test avec un tableau dont toutes les valeurs sont vides
			$this->Item->data = array(
				'Item' => array(
					'firstname' => '',
					'lastname' => ''
				)
			);
			$this->assertTrue( $this->Item->allEmpty( array( 'firstname' => '' ), 'lastname' ) );

			// -----------------------------------------------------------------

			// test avec un tableau dont une valeur n'est pas vide
			$this->Item->data = array(
				'Item' => array(
					'firstname' => 'fn',
					'lastname' => ''
				)
			);
			$this->assertFalse( $this->Item->allEmpty( array( 'firstname' => 'fn' ), 'lastname' ) );
		}

		/**
		* Test de la fonction unbindModelAll
		*/
		public function testUnbindModelAll() {
			// vérification qu'après appel de la fonction le modèle Item n'ai plus de relation avec Partitiem
			$this->Item->unbindModelAll();
			$this->assertEqual( array(), $this->Item->hasOne );
			$this->Item->bindModel( array( 'hasOne' => array( 'Partitem' ) ), false );
		}

		/**
		* FIXME: fieldList change-t'il quelque chose
		*/
		public function testSaveAll() {

			// mise en place de la règle de validation
			$this->Item->validate = array(
				'firstname' => array(
					'rule' => 'notEmpty'
				)
			);

			// test pour toutes les informations fournies et que le règle d'atomicité est donnée
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

			// test quand le champs obligatoire est manquant et que le règle d'atomicité est donnée
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

			// test quand tous les champs sont donnés et que le règle d'atomicité est à faux
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

			// test quand le champs obligatoire est manquant et que le règle d'atomicité est à faux
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
