<?php
    class Foyer extends AppModel
    {
        var $name = 'Foyer';

        var $belongTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasMany = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'foyer_id'
            ),
            'ModeContact' => array(
                'classname'     => 'ModeContact',
                'foreignKey'    => 'foyer_id'
            ),
            'AdressesFoyer' => array(
                'classname'     => 'AdressesFoyer',
                'foreignKey'    => 'foyer_id'
            )
        );
    }
?>