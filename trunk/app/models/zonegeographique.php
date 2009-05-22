<?php
    class Zonegeographique extends AppModel
    {
        var $name = 'Zonegeographique';
        var $useTable = 'zonesgeographiques';

        //---------------------------------------------------------------------

        var $hasAndBelongsToMany = array(
            'User' => array(
                'classname' => 'User',
                'joinTable' => 'users_zonesgeographiques',
                'foreignKey' => 'zonegeographique_id',
                'associationForeignKey' => 'user_id'
            )
        );

    }

?>
