<?php
    class Referent extends AppModel
    {

        var $name = 'Referent';
        var $useTable = 'referents';

        var $belongsTo = array(
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            )
        );

        var $validate = array(
            'numero_poste' => array(
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce N° de téléphone est déjà utilisé'
//                 ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le N° de poste est composé de 10 chiffres'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'qual' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nom' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'prenom' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'fonction' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'email' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'structurereferente_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );
    }
?>