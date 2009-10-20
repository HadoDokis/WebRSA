<?php
    class Typenotif extends AppModel
    {
        var $name = 'Typenotif';
        var $useTable = 'typesnotifs';
        var $displayField = 'libelle';
        var $order = 'Typenotif.id ASC';

        var $hasMany = array(
            'Propopdo' => array(
                'classname' => 'Propopdo',
                'foreignKey' => 'typenotif_id'
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