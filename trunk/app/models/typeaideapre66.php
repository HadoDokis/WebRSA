<?php
    class Typeaideapre66 extends AppModel
    {
        public $name = 'Typeaideapre66';

        var $belongsTo = array( 'Themeapre66' );

        var $actsAs = array( 'Autovalidate' );

        var $hasAndBelongsToMany = array(
            'Pieceaide66' => array(
                'classname'             => 'Pieceaide66',
                'joinTable'             => 'typesaidesapres66_piecesaides66',
                'foreignKey'            => 'typeaideapre66_id',
                'associationForeignKey' => 'pieceaide66_id',
                'with'                  => 'Typeaideapre66Pieceaide66'
            )
        );


    }
?>