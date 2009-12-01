<?php
    class Actprof extends AppModel
    {
        var $name = 'Actprof';
        var $actsAs = array( 'Enumerable', 'Frenchfloat' => array( 'fields' => array( 'montantaide', 'coutform', 'dureeform' ) ) );


        var $validate = array(
            'nomemployeur' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'adresseemployeur' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'typecontratact' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'intituleformation' => array(
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