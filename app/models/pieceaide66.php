<?php
    class Pieceaide66 extends AppModel
    {
        public $name = 'Pieceaide66';

        var $hasAndBelongsToMany = array(
            'Aideapre66' => array(
                'classname'             => 'Aideapre66',
                'joinTable'             => 'aidesapres66_piecesaides66',
                'foreignKey'            => 'pieceaide66_id',
                'associationForeignKey' => 'aideapre66_id',
                'with'                  => 'Aideapre66Pieceaide66'
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