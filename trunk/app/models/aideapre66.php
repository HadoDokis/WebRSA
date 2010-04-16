<?php
    class Aideapre66 extends AppModel
    {
        public $name = 'Aideapre66';

        var $belongsTo = array( 'Themeapre66' );

        var $actsAs = array( 'Autovalidate' );

        var $hasAndBelongsToMany = array(
            'Pieceaide66' => array(
                'classname'             => 'Pieceaide66',
                'joinTable'             => 'aidesapres66_piecesaides66',
                'foreignKey'            => 'aideapre66_id',
                'associationForeignKey' => 'pieceaide66_id',
                'with'                  => 'Aideapre66Pieceaide66'
            )
        );


    }
?>