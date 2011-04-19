<?php
    class Sitecov58 extends AppModel
    {
        public $name = 'Sitecov58';

        public $order = array( 'Sitecov58.name ASC' );

        public $actsAs = array(
            'Autovalidate',
            'ValidateTranslate'
        );

        public $hasMany = array(
            'Cov58' => array(
                'className' => 'Cov58',
                'foreignKey' => 'sitecov58_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
            )
        );
    }
?>