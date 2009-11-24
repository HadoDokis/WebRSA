<?php 
    class Comiteexamenapre extends AppModel
    {
        var $name = 'Comiteexamenapre';
        var $actsAs = array( 'Enumerable' );

        var $hasAndBelongsToMany = array(
            'Participantcomiteexamen' => array(
                'className'              => 'Participantcomiteexamen',
                'joinTable'              => 'comitesexamenapres_participantscomitesexamen',
                'foreignKey'             => 'participantcomiteexamen_id',
                'associationForeignKey'  => 'comiteexamenapre_id'
            ),
            'Apre' => array(
                'className'              => 'Apre',
                'joinTable'              => 'apres_comitesexamenapres',
                'foreignKey'             => 'apre_id',
                'associationForeignKey'  => 'comiteexamenapre_id'
            )
        );

        var $validate = array(
            'datecomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'heurecomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'lieucomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'intitulecomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'observationcomite' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );
    }
?>