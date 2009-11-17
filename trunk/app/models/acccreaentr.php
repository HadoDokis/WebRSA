<?php
    class Acccreaentr extends AppModel
    {
        var $name = 'Acccreaentr';
//         var $actsAs = array( 'Enumerable' );
        var $actsAs = array( 'Enumerable', 'Frenchfloat' => array( 'fields' => array( 'montantaide' ) ) );

        var $validate = array(
            'montantaide' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer une valeur numérique.',
                'allowEmpty' => true
            )
        );

        var $enumFields = array(
            'nacre' => array( 'type' => 'no', 'domain' => 'default' ),
            'microcredit' => array( 'type' => 'no', 'domain' => 'default' ),
        );

        var $hasAndBelongsToMany = array(
            'Pieceacccreaentr' => array(
                'className'             => 'Pieceacccreaentr',
                'joinTable'             => 'accscreaentr_piecesaccscreaentr',
                'foreignKey'            => 'acccreaentr_id',
                'associationForeignKey' => 'pieceacccreaentr_id',
                'with'                  => 'AcccreaentrPieceacccreaentr'
            )
        );
    }
?>