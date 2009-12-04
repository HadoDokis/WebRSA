<?php
    class Permisb extends AppModel
    {
        var $name = 'Permisb';
        var $actsAs = array( 'Enumerable', 'Frenchfloat' => array( 'fields' => array( 'coutform', 'dureeform' ) ) );

        var $validate = array(
            'nomautoecole' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'adresseautoecole' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'coutform' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                ),
                array(
                    'rule' => array( 'range', -1, 1000 ),
                    'message' => 'Veuillez saisir un montant compris entre 0 et 1000€ maximum.'
                )
            ),
            'dureeform' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
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