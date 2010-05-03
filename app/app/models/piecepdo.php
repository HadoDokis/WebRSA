<?php
    class Piecepdo extends AppModel
    {
        var $name = 'Piecepdo';
//         var $displayField = 'Piecepdo.dateajout ASC';

        var $belongsTo = array(
            'Propopdo' => array(
                'classname' => 'Propopdo',
                'foreignKey' => 'propopdo_id'
            )
        );
    }
?>