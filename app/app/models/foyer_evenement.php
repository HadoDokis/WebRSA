<?php
    class FoyerEvenement extends AppModel
    {
        var $name = 'FoyerEvenement';
        var $useTable = 'foyers_evenements';


        var $belongsTo = array(
            'Foyer' => array(
                'classname' => 'Foyer',
                'foreignKey' => 'foyer_id'
            ),
            'Evenement' => array(
                'classname' => 'Evenement',
                'foreignKey' => 'evenement_id'
            )
        );
    }
?>