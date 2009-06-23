<?php
    class Modecontact extends AppModel
    {
        var $name = 'Modecontact';
        var $useTable = 'modescontact';

        var $belongsTo = array(
            'Foyer' => array(
                'classname'     => 'Foyer',
                'foreignKey'    => 'foyer_id'
            )
        );
    }
?>