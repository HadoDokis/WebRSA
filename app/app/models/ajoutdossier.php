<?php
    class Ajoutdossier extends AppModel
    {
        var $name = 'Ajoutdossier';
        var $useTable = false;

        //*********************************************************************

        var $validate = array(
            'serviceinstructeur_id' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );
    }
?>