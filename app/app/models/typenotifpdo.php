<?php
    class Typenotifpdo extends AppModel
    {
        var $name = 'Typenotifpdo';
        var $useTable = 'typesnotifspdos';
        var $displayField = 'libelle';
        var $order = 'Typenotifpdo.id ASC';

        var $hasAndBelongsToMany = array(
            'Propopdo' => array(
                'classname' => 'Propopdo',
                'joinTable' => 'propospdos_typesnotifspdos',
                'foreignKey' => 'typenotifpdo_id',
                'associationForeignKey' => 'propopdo_id'
            )
        );


        var $validate = array(
            'libelle' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'modelenotifpdo' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );
    }
?>