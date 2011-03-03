<?php
    class Objetentretien extends AppModel
    {
        public $name = 'Objetentretien';

        public $displayField = 'name';

        public $order = 'Objetentretien.id ASC';

        public $actsAs = array(
            'Autovalidate',
            'Formattable',
            'Enumerable'
        );

        public $hasMany = array(
            'Entretien' => array(
                'className' => 'Entretien',
                'foreignKey' => 'objetentretien_id',
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