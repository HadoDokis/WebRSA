<?php
    class Infopoleemploi extends AppModel
    {
        var $name = 'Infopoleemploi';
        var $useTable = 'infospoleemploi';

        //*********************************************************************

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );
    }
?>