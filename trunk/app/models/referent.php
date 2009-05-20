<?php
    class Referent extends AppModel
    {

        var $name = 'Referent';
        var $useTable = 'referents';

        var $belongsTo = array(
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            )
        );
    }
?>