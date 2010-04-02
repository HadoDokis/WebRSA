<?php
    class Permanence extends AppModel
    {
        var $name = 'Permanence';

        var $useTable = 'permanences';
        var $displayField = 'libpermanence';

        var $belongsTo = array(
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            )
        );

        var $validate = array(
            'structurereferente_id' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'libpermanence' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typevoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
//             'numvoie' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
            'nomvoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'codepos' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
//             'canton' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
            'ville' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numtel' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );
    }
?>