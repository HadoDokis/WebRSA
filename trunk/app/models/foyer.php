<?php
    class Foyer extends AppModel
    {
        var $name = 'Foyer';

        var $hasOne = array(
            'Dspf'
        );

        var $belongTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasMany = array(
            'Adressefoyer' => array(
                'classname'     => 'Adressefoyer',
                'foreignKey'    => 'foyer_id'
            ),
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