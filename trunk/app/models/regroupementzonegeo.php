<?php
    class Regroupementzonegeo extends AppModel
    {
        var $name = 'Regroupementzonegeo';
        var $useTable = 'regroupementszonesgeo';

        //---------------------------------------------------------------------

        var $hasAndBelongsToMany = array(
            'Zonegeographique' => array(
                'classname' => 'Regroupementzonegeo',
                'joinTable' => 'zonesgeographiques_regroupementszonesgeo',
                'foreignKey' => 'regroupementzonegeo_id',
                'associationForeignKey' => 'zonegeographique_id'
            )
        );

    }

?>