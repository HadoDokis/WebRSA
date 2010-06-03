<?php
    class Statutdecisionpdo extends AppModel
    {
        var $name = 'Statutdecisionpdo';

        var $displayField = 'libelle';

        var $hasAndBelongsToMany = array(
            'Propopdo' => array( 'with' => 'PropopdoStatutdecisionpdo' )
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