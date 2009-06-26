<?php
    class Ressourcemensuelle extends AppModel
    {
        var $name = 'Ressourcemensuelle';
        var $useTable = 'ressourcesmensuelles';

        var $belongsTo = array(
            'Ressource' => array(
                'classname'     => 'Ressource',
                'foreignKey'    => 'ressource_id'
            )
        );

        var $hasMany = array(
            'Detailressourcemensuelle' => array(
                'classname'     => 'Detailressourcemensuelle',
                'foreignKey'    => 'ressourcemensuelle_id'
            )
        );

        var $validate = array(
            'moisress' => array(
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            ),
            'nbheumentra' => array(
                array(
                    'rule'          => array( 'comparison', '<=', 744 ),
                    'message'       => 'Veuillez entrer un nombre de 744 au maximum ',
                    'allowEmpty'    => true
                ),
                array(
                    'rule'          => 'numeric',
                    'message'       => 'Veuillez entrer un nombre valide',
                    'allowEmpty'    => true
                )
            ),
            // Montant d'abattement / neutralisation
            'mtabaneu' => array(
                array(
                    'rule'          => array( 'range', 0, 33333332 ),
                    'message'       => 'Veuillez entrer un montant compris entre 0 et 33 333 332',
                    'allowEmpty'    => true
                ),
                array(
                    'rule'          => array( 'between', 0, 11 ),
                    'message'       => 'Veuillez entrer au maximum 11 caractères',
                    'allowEmpty'    => true
                ),
                array(
                    'rule'          => 'numeric',
                    'message'       => 'Veuillez entrer un nombre valide',
                    'allowEmpty'    => true
                )
            ),
        );
    }
?>
