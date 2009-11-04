<?php 
    class Formqualif extends AppModel
    {
        var $name = 'Formqualif';

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