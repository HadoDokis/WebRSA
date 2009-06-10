<?php
    // FIXME: passe pas dedans ?
    class Detailressourcemensuelle extends AppModel
    {
        var $name = 'Detailressourcemensuelle';
        var $useTable = 'detailsressourcesmensuelles';

        var $validate = array (
            'dfpercress' => array (
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            ),
            'mtnatressmen' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer un nombre valide'
            ),
        );
    }
?>
