<?php

    class User extends AppModel {

        var $name = 'User';

        var $belongsTo = array(
            'Group'=> array(
                'className'  => 'Group',
                'conditions' => '',
                'order'      => '',
                'dependent'  => false,
                'foreignKey' => 'group_id'
            ),
            'Serviceinstructeur'=> array(
                'className'  => 'Serviceinstructeur',
                'conditions' => '',
                'order'      => '',
                'dependent'  => false,
                'foreignKey' => 'serviceinstructeur_id'
            )
        );

        var $hasAndBelongsToMany = array(
            'Zonegeographique' => array(
                'classname'             => 'Zonegeographique',
                'joinTable'             => 'users_zonesgeographiques',
                'foreignKey'            => 'user_id',
                'associationForeignKey' => 'zonegeographique_id'
            )
        );
    }
?>
