<?php
    class Typerdv extends AppModel
    {
        var $name = 'Typerdv';
        var $useTable = 'typesrdv';
        var $displayField = 'libelle';
        var $order = 'Typerdv.id ASC';

        var $hasMany = array(
            'Rendezvous' => array(
                'classname' => 'Rendezvous',
                'foreignKey' => 'typerdv_id'
            )
        );


        var $validate = array(
            'libelle' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'modelenotifrdv' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );
    }
?>