<?php
    class Permisb extends AppModel
    {
        var $name = 'Permisb';
        var $actsAs = array( 'Enumerable', 'Frenchfloat' => array( 'fields' => array( 'coutform', 'dureeform' ) ) );

        var $validate = array(
            'coutform' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer une valeur numérique.',
                'allowEmpty' => true
            ),
            'dureeform' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer une valeur numérique.',
                'allowEmpty' => true
            )
        );

        var $hasAndBelongsToMany = array(
            'Piecepermisb' => array(
                'className'             => 'Piecepermisb',
                'joinTable'             => 'permisb_piecespermisb',
                'foreignKey'            => 'permisb_id',
                'associationForeignKey' => 'piecepermisb_id',
                'with'                  => 'PermisbPiecepermisb'
            )
        );
    }
?>