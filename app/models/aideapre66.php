<?php
    class Aideapre66 extends AppModel
    {
        public $name = 'Aideapre66';

        var $belongsTo = array(
			'Typeaideapre66',
            'Apre66'
		);

        var $hasOne = array( 'Fraisdeplacement66' );

        var $actsAs = array(
			'Autovalidate',
			'Formattable' => array(
				'suffix' => array( 'typeaideapre66_id' ),
			),
            'Enumerable' => array(
                'fields' => array(
                    'virement' => array( 'type' => 'virement', 'domain' => 'aideapre66' ),
                    'versement' => array( 'type' => 'versement', 'domain' => 'aideapre66' ),
                    'autorisationvers' => array( 'type' => 'no', 'domain' => 'aideapre66' ),
                    'decisionapre' => array( 'type' => 'decisionapre', 'domain' => 'aideapre66' ),
                )
            )
		);


        var $validate = array(
			'themeapre66_id' => array(
				array(
					'rule' => 'notEmpty'
				)
			)
        );

        /**
        *
        */

        /*function beforeSave( $options = array() ) {

            $return = parent::beforeSave( $options );

//             $this->data[$this->alias]['apre_id'] = 14;
// debug($this->data);

            if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'typeaideapre66_id', $this->data[$this->name] ) ) {
                $this->data = Set::insert( $this->data, "{$this->alias}.typeaideapre66_id", suffix( Set::extract( $this->data, "{$this->alias}.typeaideapre66_id" ) ) );
            }

            return $return;
        }*/
    }
?>