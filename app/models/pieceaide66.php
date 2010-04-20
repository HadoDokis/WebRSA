<?php
    class Pieceaide66 extends AppModel
    {
        public $name = 'Pieceaide66';

        var $hasAndBelongsToMany = array(
            'Typeaideapre66' => array(
                'classname'             => 'Typeaideapre66',
                'joinTable'             => 'typesaidesapres66_piecesaides66',
                'foreignKey'            => 'pieceaide66_id',
                'associationForeignKey' => 'typeaideapre66_id',
                'with'                  => 'Typeaideapre66Pieceaide66'
            )
        );

        var $validate = array(
            'name' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

    }
?>