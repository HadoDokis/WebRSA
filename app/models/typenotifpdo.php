<?php
    class Typenotifpdo extends AppModel
    {
        var $name = 'Typenotifpdo';
        var $useTable = 'typesnotifspdos';
        var $displayField = 'libelle';
        var $order = 'Typenotifpdo.id ASC';



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