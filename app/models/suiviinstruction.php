<?php
    class Suiviinstruction extends AppModel
    {
        var $name = 'Suiviinstruction';
        var $useTable = 'suivisinstruction';


        var $belongsTo = array(
            'Dossier' => array(
                'classname' => 'Dossier',
                'foreignKey' => 'id'
            )
        );


        var $validate = array(
            'etatirsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'date_etat_instruction' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nomins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numdepins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeserins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numcomins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numagrins' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )

        );
    }
?>
