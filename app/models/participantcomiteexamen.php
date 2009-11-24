<?php
    class Participantcomiteexamen extends AppModel
    {
        var $name = 'Participantcomiteexamen';
        var $useTable = 'participantscomitesexamen';
        var $actsAs = array( 'Enumerable' );

        var $hasAndBelongsToMany = array(
            'Comiteexamenapre' => array(
                'className'              => 'Comiteexamenapre',
                'joinTable'              => 'comitesexamenapres_participantscomitesexamen',
                'foreignKey'             => 'comiteexamenapre_id',
                'associationForeignKey'  => 'participantcomiteexamen_id'
            )
        );


        var $validate = array(
            'nom' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'qual' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'prenom' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'organisme' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'fonction' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
//             'numtel' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'mail' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             )
        );
    }
?>