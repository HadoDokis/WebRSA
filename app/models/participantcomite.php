<?php
    class Participantcomite extends AppModel
    {
        var $name = 'Participantcomite';
        var $useTable = 'participantscomites';


        var $order = 'Participantcomite.id ASC';

        var $actsAs = array(
            'Enumerable'
        );

        var $hasAndBelongsToMany = array(
            'Comiteapre' => array(
                'className'              => 'Comiteapre',
                'joinTable'              => 'comitesapres_participantscomites',
                'foreignKey'             => 'participantcomite_id',
                'associationForeignKey'  => 'comiteapre_id',
                'with'                   => 'ComiteapreParticipantcomite'
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