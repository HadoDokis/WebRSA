<?php
    class Acqmatprof extends AppModel
    {
        var $name = 'Acqmatprof';

        var $actsAs = array( 'Frenchfloat' => array( 'fields' => array( 'montantaide' ) ) );

        var $validate = array(
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

        var $hasAndBelongsToMany = array(
            'Pieceacqmatprof' => array(
                'className'             => 'Pieceacqmatprof',
                'joinTable'             => 'acqsmatsprofs_piecesacqsmatsprofs',
                'foreignKey'            => 'acqmatprof_id',
                'associationForeignKey' => 'pieceacqmatprof_id',
                'with'                  => 'AcqmatprofPieceacqmatprof'
            )
        );
    }
?>