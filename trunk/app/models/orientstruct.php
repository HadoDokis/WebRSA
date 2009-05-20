<?php
    class Orientstruct extends AppModel
    {
        var $name = 'Orientstruct';
        var $useTable = 'orientsstructs';


        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            )
        );
    }
?>