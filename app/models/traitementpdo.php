<?php
    class Traitementpdo extends AppModel
    {
        public $name = 'Traitementpdo';

        


        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'hascourrier',
                    'hasrevenu',
                    'haspiecejointe',
                )
            )
        );

        var $belongsTo = array(
            'Propopdo' => array(
                'classname'     => 'Propopdo',
                'foreignKey'    => 'propopdo_id'
            )
        );

    }
?>