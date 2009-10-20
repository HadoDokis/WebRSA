<?php
    class Decisionpdo extends AppModel
    {
        var $name = 'Decisionpdo';
        var $useTable = 'decisionspdos';
        var $displayField = 'libelle';
        var $order = 'Decisionpdo.id ASC';

        var $hasMany = array(
            'Propopdo' => array(
                'classname' => 'Propopdo',
                'foreignKey' => 'decisionpdo_id'
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