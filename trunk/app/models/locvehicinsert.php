<?php
    class Locvehicinsert extends AppModel
    {
        var $name = 'Locvehicinsert';

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