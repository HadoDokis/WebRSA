<?php
	class AppModelTest extends CakeTestCase
	{

		public $Apple = null;

		/**
		 * Fixtures.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple',
			'app.Actionfp93',
			'app.Adresseprestatairefp93',
		);

		/**
		 * Set up the test
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$this->Apple = ClassRegistry::init( 'Apple' );

			$this->Apple->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => array( 'notEmpty' ),
						'required' => true,
						'on' => 'create',
						'message' => null
					)
				)
			);
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->Apple );
		}

		/**
		 * Test de la méthode AppModel::save()
		 *
		 * @return void
		 */
//		public function testSave() {
//			$this->Apple->create( array( 'color' => 'red' ) );
//			$this->assertEqual( $this->Apple->save(), false );
//
//			$this->Apple->create( array( 'name' => 'Bintje', 'color' => 'red' ) );
//			$this->assertEqual( $this->Apple->save(), true );
//		}

		/**
		 * Test de la méthode AppModel::save()
		 *
		 * @return void
		 */
		public function testSaveAll() {
			$data = array(
				array(
					'color' => 'red'
				),
				array(
					'name' => 'Bintje',
					'color' => 'red'
				),
			);
			$result = $this->Apple->saveAll( $data, array( 'atomic' => false ) );
			$this->assertEqual( $result, false );

			$data = array(
				array(
					'name' => 'Bintje',
					'color' => 'red'
				),
			);
			$result = $this->Apple->saveAll( $data, array( 'atomic' => false ) );
			$this->assertEqual( $result, true );
		}

		/**
		 * Test de la méthode AppModel::beforeFind() lorsque l'on force les champs
		 * virtuels.
		 */
		public function testBeforefindForceVirtualFields() {
			$this->Actionfp93 = ClassRegistry::init( 'Actionfp93' );

			$query = array(
				'fields' => array(
					'Actionfp93.name',
					'Adresseprestatairefp93.name'
				),
				'joins' => array(
					$this->Actionfp93->join( 'Adresseprestatairefp93' )
				),
				'conditions' => array(
					'Adresseprestatairefp93.name' => 'Foo'
				)
			);

			$this->Actionfp93->forceVirtualFields = true;
			$result = $this->Actionfp93->beforeFind( $query );

			$expected = array(
				'fields' => array(
					'Actionfp93.name',
					'( "Adresseprestatairefp93"."adresse" || \', \' || "Adresseprestatairefp93"."codepos" || \' \' || "Adresseprestatairefp93"."localite" ) AS  "Adresseprestatairefp93__name"'
				),
				'joins' => array(
					array(
						'table' => '"adressesprestatairesfps93"',
						'alias' => 'Adresseprestatairefp93',
						'type' => 'LEFT',
						'conditions' => '"Actionfp93"."adresseprestatairefp93_id" = "Adresseprestatairefp93"."id"',
					),
				),
				'conditions' => array(
					'( "Adresseprestatairefp93"."adresse" || \', \' || "Adresseprestatairefp93"."codepos" || \' \' || "Adresseprestatairefp93"."localite" )' => 'Foo',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
