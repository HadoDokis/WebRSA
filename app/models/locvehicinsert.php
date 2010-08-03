<?php
    class Locvehicinsert extends AppModel
    {
        var $name = 'Locvehicinsert';

        var $actsAs = array(
            'Aideapre',
            'Enumerable', // FIXME: pas besoin ?
            'Frenchfloat' => array( 'fields' => array( 'montantaide', 'dureelocation' ) )
        );


        var $validate = array(
            'societelocation' => array(
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
                )/*,
                array(
                    'rule' => array( 'inclusiveRange', 0, 700 ),
                    'message' => 'Veuillez saisir un montant compris entre 0 et 700€ / 6 mois maximum.'
                )*/
            ),
            'dureelocation' => array(
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
                    'rule' => array( 'comparison', '>=', 0 ),
                    'message' => 'Veuillez saisir une valeur positive.'
                )
            )
        );

        var $hasAndBelongsToMany = array(
            'Piecelocvehicinsert' => array(
                'className'             => 'Piecelocvehicinsert',
                'joinTable'             => 'locsvehicinsert_pieceslocsvehicinsert',
                'foreignKey'            => 'locvehicinsert_id',
                'associationForeignKey' => 'piecelocvehicinsert_id',
                'with'                  => 'LocvehicinsertPiecelocvehicinsert'
            )
        );
    }
?>