<?php
    class Piecepdo extends AppModel
    {
        var $name = 'Piecepdo';

        var $belongsTo = array(
            'Propopdo' => array(
                'classname' => 'Propopdo',
                'foreignKey' => 'propopdo_id'
            )
        );
    }
?>