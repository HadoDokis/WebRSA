<?php
    class Typepdo extends AppModel
    {
        var $name = 'Typepdo';
        var $useTable = 'typespdos';
        var $displayField = 'libelle';
        var $order = 'Typepdo.id ASC';

        var $hasMany = array(
            'Propopdo' => array(
                'classname' => 'Propopdo',
                'foreignKey' => 'typepdo_id'
            )
        );


        var $validate = array(
            'libelle' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );
    }
?>