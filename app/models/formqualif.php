<?php 
    class Formqualif extends AppModel
    {
        var $name = 'Formqualif';

        var $validate = array(
            'montantaide' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer une valeur numérique.',
                'allowEmpty' => true
            ),
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
            'Pieceformqualif' => array(
                'className'             => 'Pieceformqualif',
                'joinTable'             => 'formsqualifs_piecesformsqualifs',
                'foreignKey'            => 'formqualif_id',
                'associationForeignKey' => 'pieceformqualif_id',
                'with'                  => 'FormqualifPieceformqualif'
            )
        );
    }
?>