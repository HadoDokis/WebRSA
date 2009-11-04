<?php
    class Permisb extends AppModel
    {
        var $name = 'Permisb';
        var $actsAs = array( 'Enumerable' );


        var $hasAndBelongsToMany = array(
            'Piecepermisb' => array(
                'className'             => 'Piecepermisb',
                'joinTable'             => 'permisb_piecespermisb',
                'foreignKey'            => 'permisb_id',
                'associationForeignKey' => 'piecepermisb_id',
                'with'                  => 'PermisbPiecepermisb'
            )
        );
    }
?>