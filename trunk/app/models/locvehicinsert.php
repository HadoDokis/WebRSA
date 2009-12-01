<?php
    class Locvehicinsert extends AppModel
    {
        var $name = 'Locvehicinsert';

        var $actsAs = array( 'Enumerable', 'Frenchfloat' => array( 'fields' => array( 'montantaide', 'dureelocation' ) ) );


        var $validate = array(
            'societelocation' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'montantaide' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
            'dureelocation' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            )
        );

        var $hasAndBelongsToMany = array(
            'Piecelocvehicinsert' => array(
                'className'             => 'Piecelocvehicinsert',
                'joinTable'             => 'locsvehicinsert_pieceslocsvehicinsert',
                'foreignKey'            => 'locvehicinsert_id',
                'associationForeignKey' => 'piecelocvehicinsert_id',
                'with'                  => 'LocvehicinsertPiecelocvehicinsert'
            )
        );
    }
?>