<?php
    class Creance extends AppModel
    {
        var $name = 'Creance';
        var $useTable = 'creances';

        var $belongsTo = array(
            'Foyer' => array(
                'classname' => 'Foyer',
                'foreignKey' => 'foyer_id'
            )
        );

    }
?>