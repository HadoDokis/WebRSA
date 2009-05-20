<?php

    class User extends AppModel {

        var $name = 'User';

        var $belongsTo = array(
                'Group'=>array(
                        'className'  => 'Group',
                        'conditions' => '',
                        'order'      => '',
                        'dependent'  => false,
                        'foreignKey' => 'group_id')
                 );
    }
?>
