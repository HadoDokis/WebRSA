<?php
    class Typocontrat extends AppModel
    {
        var $name = 'Typocontrat';
        var $useTable = 'typoscontrats';
        var $displayField = 'lib_typo';
        var $order = 'Typocontrat.id ASC';

        var $hasMany = array(
            'Contratinsertion' => array(
                'classname' => 'Contratinsertion',
                'foreignKey' => 'typocontrat_id'
            )
        );


        var $validate = array(
            'lib_typo' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );
    }
?>