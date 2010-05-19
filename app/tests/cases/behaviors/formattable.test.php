<?php
	if( !defined( 'CAKEPHP_UNIT_TEST_EXECUTION' ) ) {
		define( 'CAKEPHP_UNIT_TEST_EXECUTION', 1 );
	}

	ClassRegistry::config(array('ds' => 'test_suite'));

    class FormattableBehaviorTest extends CakeTestCase
    {

		var $fixtures = array( 'item', 'jeton' );

		/**
		* Création d'un modèle sans behavior et association du behavior à tester.
		* Exécuté avant chaque test.
		* TODO: une fonction pour vérifier les paramètres par défaut
		*/

        function startTest() {
			$this->Item =& ClassRegistry::init( 'Item' );

			// Detach all behaviors
			$behaviors = array_values( $this->Item->Behaviors->attached() );
			foreach( $behaviors as $behavior ) {
				$this->Item->Behaviors->detach( $behavior );
			}

			// Attach the behavior we're testing
			$settings = array(
				'trim' => true,
				'null' => true,
				'suffix' => array( 'category_id' ),
				'phone' => array( 'tel', 'fax' ),
				'amount' => array( 'montant' )
			);
			$this->Item->Behaviors->attach( 'Formattable', $settings );
        }

        /**
		* Trois tests en un.
		*/

        function testSave() {
			$this->Item->begin();

			/// Enregistrement
			$item = array(
				'Item' => array(
					'id' => 1,
					'name_a' => ' ',
					'name_b' => '',
					'version_a' => 0,
					'version_n' => '',
					'description_a' => '',
					'description_b' => '',
					'modifiable_a' => '',
					'modifiable_b' => '',
					'date_a' => '',
					'date_b' => '',
					'tel' => '04 04 04 04 04',
					'fax' => '',
					'category_id' => '5_3',
					'foo' => '',
					'montant' => '30 000,00'
				)
			);

			$this->Item->create( $item );
			$result = $this->Item->save();
			$this->assertTrue( !empty( $result ) );

			//------------------------------------------------------------------

			/// Vérification de ce qui a été enregistré
			$expected = array(
				'Item' => array(
					'id' => '1',
					'version_a' => '0',
					'modifiable_a' => '',
					'date_a' => '',
					'name_a' => '',
					'name_b' => NULL,
					'version_n' => NULL,
					'description_a' => '',
					'description_b' => NULL,
					'modifiable_b' => NULL,
					'date_b' => NULL,
					'tel' => '0404040404',
					'fax' => NULL,
					'category_id' => '3',
					'foo' => NULL,
					'montant' => '30000.00'
				)
			);
			$this->assertEqual( $result, $expected );

			//------------------------------------------------------------------

			$conn = ConnectionManager::getInstance();
			$driver = $conn->config->{$this->Item->useDbConfig}['driver'];
			/// FIXME: Firstname 1 ..
			/// Vérification de la lecture de ce qui a été enregistré
			$result = $this->Item->findById( $this->Item->id, null, null, -1 );
			$expected = array(
				'Item' => array(
                    'id' => '1',
					'firstname' => 'Firstname n°1',
					'lastname' => 'Lastname n°1',
                    'name_a' => '',
                    'name_b' => NULL,
                    'version_a' => '0',
                    'version_n' => NULL,
                    'description_a' => '',
                    'description_b' => NULL,
					// FIXME: NULL -> '0' avec MySQL et Postgres
                    'modifiable_a' => '0',
                    'modifiable_b' => NULL,
					// FIXME: NULL -> '0000-00-00' avec MySQL / '1970-01-01' avec Postgres
                    'date_a' => ife( $driver == 'postgres', '1970-01-01', '0000-00-00' ),
                    'date_b' => NULL,
                    'tel' => '0404040404',
                    'fax' => NULL,
                    'category_id' => '3',
                    'foo' => NULL,
                    'bar' => NULL,
                    'montant' => '30000',
					//'fullname' => 'Firstname n°1 Lastname n°1', // FIXME: ça vient d'où ?
				)
			);
			$this->assertEqual( $result, $expected );

			$this->Item->rollback();
        }
    }
?>