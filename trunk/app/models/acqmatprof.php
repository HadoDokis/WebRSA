<?php
    class Acqmatprof extends AppModel
    {
        var $name = 'Acqmatprof';

        var $validate = array(
            'montantaide' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer une valeur numérique.',
                'allowEmpty' => true
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