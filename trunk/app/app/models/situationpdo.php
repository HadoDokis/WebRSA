<?php
    class Situationpdo extends AppModel
    {
        var $name = 'Situationpdo';

        var $displayField = 'libelle';

        var $hasAndBelongsToMany = array(
            'Propopdo' => array( 'with' => 'PropopdoSituationpdo' )
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