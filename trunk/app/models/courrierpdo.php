<?php
    class Courrierpdo extends AppModel
    {
        public $name = 'Courrierpdo';

        public $actsAs = array(
            'Autovalidate',
            'ValidateTranslate',
            'Formattable'
        );


        public $hasMany = array(
            'Textareacourrierpdo' => array(
                'className' => 'Textareacourrierpdo',
                'foreignKey' => 'courrierpdo_id',
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



        public $hasAndBelongsToMany = array(
            'Traitementpdo' => array(
                'className' => 'Traitementpdo',
                'joinTable' => 'courrierspdos_traitementspdos',
                'foreignKey' => 'courrierpdo_id',
                'associationForeignKey' => 'traitementpdo_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'deleteQuery' => '',
                'insertQuery' => '',
                'with' => 'CourrierpdoTraitementpdo'
            )
        );

    }
?>
