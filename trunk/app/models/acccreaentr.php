<?php
    class Acccreaentr extends AppModel
    {
        var $name = 'Acccreaentr';
//         var $actsAs = array( 'Enumerable' );
        var $actsAs = array( 'Enumerable', 'Frenchfloat' => array( 'fields' => array( 'montantaide' ) ) );

        var $validate = array(
            'nacre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'microcredit' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'montantaide' => array(
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
                    'rule' => array( 'range', -1, 2000 ),
                    'message' => 'Veuillez saisir un montant compris entre 0 et 2000€ maximum.'
                )
            )
        );

        var $enumFields = array(
            'nacre' => array( 'type' => 'no', 'domain' => 'default' ),
            'microcredit' => array( 'type' => 'no', 'domain' => 'default' ),
        );

        var $hasAndBelongsToMany = array(
            'Pieceacccreaentr' => array(
                'className'             => 'Pieceacccreaentr',
                'joinTable'             => 'accscreaentr_piecesaccscreaentr',
                'foreignKey'            => 'acccreaentr_id',
                'associationForeignKey' => 'pieceacccreaentr_id',
                'with'                  => 'AcccreaentrPieceacccreaentr'
            )
        );
    }
?>