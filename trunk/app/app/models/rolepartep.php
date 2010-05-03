<?php
    class Rolepartep extends AppModel
    {
        var $name = 'Rolepartep';

        var $hasAndBelongsToMany = array(
            'Ep' => array( 'with' => 'EpPartep' ),
            'Partep' => array( 'with' => 'EpPartep' )
        );
    }
?>