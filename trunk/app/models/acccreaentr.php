<?php
    class Acccreaentr extends AppModel
    {
        var $name = 'Acccreaentr';
        var $actsAs = array( 'Enumerable' );

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