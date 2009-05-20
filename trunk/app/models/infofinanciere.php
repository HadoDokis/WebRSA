<?php
    class Infofinanciere extends AppModel
    {
        var $name = 'Infofinanciere';
        var $useTable = 'infosfinancieres';


        var $hasMany = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );


        var $validate = array(
            'type_allocation' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'natpfcre' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeopecompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'sensopecompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dttraimoucompta' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'mtmoucompta' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'mtmoucompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),

            /*'ddregu' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            ),

            'heutraimoucompta' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            )*/
        );
    }
?>
