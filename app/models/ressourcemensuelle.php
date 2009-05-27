<?php
    class Ressourcemensuelle extends AppModel
    {
        var $name = 'Ressourcemensuelle';
        var $useTable = 'ressourcesmensuelles';

        var $belongsTo = array(
            'Ressource' => array(
                'classname'     => 'Ressource',
                'foreignKey'    => 'ressource_id'
            )
        );

        var $hasMany = array(
            'Detailressourcemensuelle' => array(
                'classname'     => 'Detailressourcemensuelle',
                'foreignKey'    => 'ressourcemensuelle_id'
            )
        );

        var $validate = array(
            'moisress' => array(
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            )
        );
    }
?>
