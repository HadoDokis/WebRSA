<?php
    class Actprof extends AppModel
    {
        var $name = 'Actprof';
        var $actsAs = array( 'Enumerable' );

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

        var $enumFields = array(
            'typecontratact' => array( 'type' => 'typecontratact', 'domain' => 'apre' ),
        );

        var $hasAndBelongsToMany = array(
            'Pieceactprof' => array(
                'className'             => 'Pieceactprof',
                'joinTable'             => 'actsprofs_piecesactsprofs',
                'foreignKey'            => 'actprof_id',
                'associationForeignKey' => 'pieceactprof_id',
                'with'                  => 'ActprofPieceactprof'
            )
        );
    }
?>