<?php 
    class Comiteexamenapre extends AppModel
    {
        var $name = 'Comiteexamenapre';
        var $actsAs = array( 'Enumerable' );

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