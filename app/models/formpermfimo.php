<?php 
    class Formpermfimo extends AppModel
    {
        var $name = 'Formpermfimo';

        var $actsAs = array( 'Frenchfloat' => array( 'fields' => array( 'coutform', 'montantaide', 'dureeform' ) ) );

        var $validate = array(
            'intituleform' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'organismeform' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
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
            ),
            'coutform' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
            'dureeform' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
            'ddform' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dfform' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
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