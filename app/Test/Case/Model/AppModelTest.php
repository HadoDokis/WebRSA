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
			'core.Apple'
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
		 * Test de la méthode AppModel::saveMany()
		 *
		 * @return void
		 */
//		public function testSaveMany() {
//			$data = array(
//				array(
//					'color' => 'red'
//				),
//				array(
//					'name' => 'Bintje',
//					'color' => 'red'
//				),
//			);
//			$result = $this->Apple->saveMany( $data, array( 'atomic' => false ) );
//			$this->assertEqual( $result, false );
//
//			$data = array(
//				array(
//					'name' => 'Bintje',
//					'color' => 'red'
//				),
//			);
//			$result = $this->Apple->saveMany( $data, array( 'atomic' => false ) );
//			$this->assertEqual( $result, true );
//		}

		/**
		 * Test de la méthode AppModel::saveAssociated()
		 *
		 * @return void
		 */
//		public function testSaveAssociated() {
//
//			$data = array(
//				'Apple' => array(
//					'name' => 'Bintje',
//					'color' => 'red'
//				),
//			);
//			$result = $this->Apple->saveAssociated( $data, array( 'atomic' => false ) );
//			$this->assertEqual( $result, true );
//		}

	}
?>
