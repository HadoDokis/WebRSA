<?php
    class Pieceaide66 extends AppModel
    {
        public $name = 'Pieceaide66';

		public $order = 'Pieceaide66.name ASC';

        public $hasAndBelongsToMany = array(
            'Typeaideapre66' => array(
                'classname'             => 'Typeaideapre66',
                'joinTable'             => 'typesaidesapres66_piecesaides66',
                'foreignKey'            => 'pieceaide66_id',
                'associationForeignKey' => 'typeaideapre66_id',
                'with'                  => 'Typeaideapre66Pieceaide66'
            ),
            'Aideapre66' => array(
                'classname'             => 'Aideapre66',
                'joinTable'             => 'aidesapres66_piecesaides66',
                'foreignKey'            => 'pieceaide66_id',
                'associationForeignKey' => 'aideapre66_id',
                'with'                  => 'Aideapre66Pieceaide66'
            )
        );

        public $validate = array(
            'name' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

    }
?>