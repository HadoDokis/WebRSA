<?php
	
	require_once( dirname( __FILE__ ).'/../cake_app_behavior_test_case.php' );

    class AutovalidateBehaviorTest extends CakeAppBehaviorTestCase
    {

        function testValidation() {
			$expected = array(
				'id' => array(
					array(
						'rule' => 'integer',
						'allowEmpty' => true,
					)
				),
				'firstname' => array(
					array(
						'rule' => 'notEmpty'
					),
					array(
						'rule' => array(
							'maxLength',
							255
						),
						'allowEmpty' => true
					)
				),
				'lastname' => array(
					array(
						'rule' => 'notEmpty'
					),
					array(
						'rule' => array(
							'maxLength',
							255
						),
						'allowEmpty' => true
					)
				),
				'name_a' => array(
					array(
						'rule' => 'notEmpty'
					),
					array(
						'rule' => array(
							'maxLength',
							255
						),
						'allowEmpty' => true
					)
				),
				'name_b' => array(
					array(
						'rule' => Array (
							'maxLength',
							255
						),
						'allowEmpty' => true
					)
				),
				'version_a' => array(
					array(
						'rule' => 'notEmpty'
					),
					array(
						'rule' => 'integer',
						'allowEmpty' => true
					)
				),
				'version_n' => array(
					array(
						'rule' => 'integer',
						'allowEmpty' => true
					)
				),
				'description_a' => array(
					array(
						'rule' => 'notEmpty'
					)
				),
				'modifiable_a' => array(
					array(
						'rule' => 'notEmpty'
					)
				),
				'date_a' => array(
					array(
						'rule' => 'notEmpty'
					)
				),
				'category_id' => array(
					array(
						'rule' => 'integer',
						'allowEmpty' => true
					)
				),
				'tel' => array(
					array(
						'rule' => Array (
							'maxLength',
							10
						),
						'allowEmpty' => true
					)
				),
				'fax' => array(
					array(
						'rule' => Array (
							'maxLength',
							10
						),
						'allowEmpty' => true
					)
				),
				'foo' => array(
					array(
						'rule' => Array (
							'maxLength',
							1
						),
						'allowEmpty' => true
					)
				),
				'bar' => array(
					array(
						'rule' => Array (
							'maxLength',
							1
						),
						'allowEmpty' => true
					)
				),
				'montant' => array(
					array(
						'rule' => 'numeric',
						'allowEmpty' => true
					)
				),
			);
			$diff = Set::diff( $this->Item->validate, $expected );
			$this->assertTrue( empty( $diff ) );
        }

        /**
		* FIXME: éviter le $model->validates ?
		*/

        function testTranslation() {
			$this->Item->create( array( 'Item' => array( 'version_a' => 'CC' ) ) );
			$this->Item->validates();
			// $this->Item->Behaviors->beforeValidate();

			$result = array();
			$validate = $this->Item->validate;
			foreach( array_keys( $validate ) as $field ) {
				$message = Set::extract( $this->Item->validate, "/{$field}/message" );
				$result[$field] = $message[0];
			}

			$expected = array(
				'id' => __( 'Validate::integer', true ), // FIXME: notEmpty ?
				'firstname' => __( 'Validate::notEmpty', true ),
				'lastname' => __( 'Validate::notEmpty', true ),
				'name_a' => __( 'Validate::notEmpty', true ),
				'name_b' => sprintf( __( 'Validate::maxLength', true ), 255 ),
				'version_a' => __( 'Validate::notEmpty', true ),
				'version_n' => __( 'Validate::integer', true ),
				'description_a' => __( 'Validate::notEmpty', true ),
				'modifiable_a' => __( 'Validate::notEmpty', true ),
				'date_a' => __( 'Validate::notEmpty', true ),
				'tel' => sprintf( __( 'Validate::maxLength', true ), 10 ),
				'fax' => sprintf( __( 'Validate::maxLength', true ), 10 ),
				'category_id' => 'Veuillez entrer un nombre entier',
				'foo' => sprintf( __( 'Validate::maxLength', true ), 1 ),
				'bar' => sprintf( __( 'Validate::maxLength', true ), 1 ),
				'montant' => __( 'Validate::numeric', true )
			);

			$this->assertEqual( $result, $expected );
        }
    }
?>
