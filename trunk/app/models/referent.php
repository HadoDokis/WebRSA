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
                array(
                    'rule' => 'isUnique',
                    'message' => 'Ce N° de téléphone est déjà utilisé'
                ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le N° de poste est composé de 10 chiffres'
                ),
//                 array(
//                     'rule' => 'numeric',
//                     'message' => 'Veuillez entrer une valeur numérique.',
//                     'allowEmpty' => true
//                 ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
	    )
	);
    }
?>