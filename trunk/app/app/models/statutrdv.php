<?php
    class Statutrdv extends AppModel
    {
        var $name = 'Statutrdv';
        var $useTable = 'statutsrdvs';
        var $displayField = 'libelle';
        var $order = 'Statutrdv.id ASC';

        var $hasMany = array(
            'Rendezvous' => array(
                'classname' => 'Rendezvous',
                'foreignKey' => 'statutrdv_id'
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