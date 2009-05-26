<?php
    class Creance extends AppModel
    {
        var $name = 'Creance';
        var $useTable = 'creances';

        var $hasAndBelongsToMany = array(
            'Foyer' => array(
                'classname' => 'Foyer',
                'joinTable' => 'foyers_creances',
                'foreignKey' => 'creance_id',
                'associationForeignKey' => 'foyer_id'
            )
        );

    }
?>