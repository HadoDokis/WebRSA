<?php
    class Evenement extends AppModel
    {
        var $name = 'Evenement';
        var $useTable = 'evenements';


        var $belongsTo = array(
            'Foyer' => array(
                'classname' => 'Foyer',
                'foreignKey' => 'foyer_id'
            )
        );
    }
?>