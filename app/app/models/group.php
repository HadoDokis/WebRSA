<?php
    class Group extends AppModel
    {
        var $name = 'Group';
        var $useTable = 'groups';

        var $validate = array(
            'name' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'parent_id' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );
    }
?>