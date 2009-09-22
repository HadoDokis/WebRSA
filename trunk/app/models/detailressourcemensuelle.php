<?php
    class Detailressourcemensuelle extends AppModel
    {
        var $name = 'Detailressourcemensuelle';
        var $useTable = 'detailsressourcesmensuelles';

        var $validate = array (
            'dfpercress' => array (
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            ),
            // Montant de la ressource selon la nature
            'mtnatressmen' => array(
                array(
                    'rule'          => array( 'comparison', '<=', 33333332 ),
                    'message'       => 'Veuillez entrer un montant compris entre 0 et 33 333 332',
                    'allowEmpty'    => true
                ),
                array(
                    'rule'          => array( 'comparison', '>=', 0 ),
                    'message'       => 'Veuillez entrer un montant compris entre 0 et 33 333 332',
                    'allowEmpty'    => true
                ),
                array(
                    'rule'      => array( 'between', 0, 11 ),
                    'message'   => 'Veuillez entrer au maximum 11 caractères',
                    'allowEmpty'    => true
                ),
                array(
                    'rule'      => 'numeric',
                    'message'   => 'Veuillez entrer un nombre valide',
                    'allowEmpty'    => true
                )
            ),
        );
    }
?>
