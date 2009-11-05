<?php
    class Acqmatprof extends AppModel
    {
        var $name = 'Acqmatprof';

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