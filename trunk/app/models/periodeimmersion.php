<?php
    class Periodeimmersion extends AppModel
    {
        var $name = 'Periodeimmersion';

        var $useTable = 'periodesimmersion';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'objectifimmersion'
                )
            ),
            'Formattable',
            'Autovalidate'
        );

        var $belongsTo = array(
            'Cui' => array(
                'classname'     => 'Cui',
                'foreignKey'    => 'cui_id'
            )
        );

        var $validate = array(
//             'typevoieentaccueil' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'numvoieentaccueil' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'nomvoieentaccueil' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'codepostalentaccueil' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'villeentaccueil' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'numtelentaccueil' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//                 array(
//                     'rule' => array( 'between', 10, 14 ),
//                     'message' => 'Le numéro de téléphone est composé de 10 chiffres'
//                 )
//             ),
//             'emailentaccueil' => array(
//                 'rule' => 'email',
//                 'message' => 'Email non valide',
//                 'allowEmpty' => true
//             ),

        );

    }
?>
