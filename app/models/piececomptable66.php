<?php
    class Piececomptable66 extends AppModel
    {
        public $name = 'Piececomptable66';

        public $order = 'Piececomptable66.name ASC';

        public $hasAndBelongsToMany = array(
            'Typeaideapre66' => array(
                'classname'             => 'Typeaideapre66',
                'joinTable'             => 'typesaidesapres66_piecescomptables66',
                'foreignKey'            => 'piececomptable66_id',
                'associationForeignKey' => 'typeaideapre66_id',
                'with'                  => 'Typeaideapre66Piececomptable66'
            ),
            'Aideapre66' => array(
                'classname'             => 'Aideapre66',
                'joinTable'             => 'aidesapres66_piecescomptables66',
                'foreignKey'            => 'piececomptable66_id',
                'associationForeignKey' => 'aideapre66_id',
                'with'                  => 'Aideapre66Piececomptable66'
            )
        );

        public $validate = array(
            'name' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

    }
?>