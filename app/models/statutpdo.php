<?php
    class Statutpdo extends AppModel
    {
        var $name = 'Statutpdo';

        var $displayField = 'libelle';

        var $hasAndBelongsToMany = array(
            'Propopdo' => array( 'with' => 'PropopdoStatutpdo' )
        );

        var $validate = array(
            'libelle' => array(
                array( 'rule' => 'notEmpty' )
            )
        );

        var $actsAs = array(
            'ValidateTranslate'
        );

    }
?>