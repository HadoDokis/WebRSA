<?php
    class Amenaglogt extends AppModel
    {
        var $name = 'Amenaglogt';
        var $actsAs = array(
            'Aideapre',
            'Enumerable' => array(
                'fields' => array(
                    'typeaidelogement' => array( 'type' => 'typeaidelogement', 'domain' => 'apre' ),
                )
            ),
            'Frenchfloat' => array( 'fields' => array( 'montantaide' ) )
        );


        var $validate = array(
            'typeaidelogement' => array(
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
                    'rule' => array( 'inclusiveRange', 0, 2000 ),
                    'message' => 'Veuillez saisir un montant compris entre 0 et 2000€ maximum.'
                )*/
            )
        );


        var $hasAndBelongsToMany = array(
            'Pieceamenaglogt' => array(
                'className'             => 'Pieceamenaglogt',
                'joinTable'             => 'amenagslogts_piecesamenagslogts',
                'foreignKey'            => 'amenaglogt_id',
                'associationForeignKey' => 'pieceamenaglogt_id',
                'with'                  => 'AmenaglogtPieceamenaglogt'
            )
        );
    }
?>