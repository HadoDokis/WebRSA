<?php 
    class Formpermfimo extends AppModel
    {
        var $name = 'Formpermfimo';

        var $actsAs = array( 'Frenchfloat' => array( 'fields' => array( 'coutform', 'montantaide', 'dureeform' ) ) );

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
            'Pieceformpermfimo' => array(
                'className'             => 'Pieceformpermfimo',
                'joinTable'             => 'formspermsfimo_piecesformspermsfimo',
                'foreignKey'            => 'formpermfimo_id',
                'associationForeignKey' => 'pieceformpermfimo_id',
                'with'                  => 'FormpermfimoPieceformpermfimo'
            )
        );
    }
?>