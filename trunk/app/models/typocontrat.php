<?php
    class Typocontrat extends AppModel
    {
        var $name = 'Typocontrat';
        var $useTable = 'typoscontrats';

        var $hasMany = array(
            'Contratinsertion' => array(
                'classname' => 'Contratinsertion',
                'foreignKey' => 'typocontrat_id'
            )
        );
    }
?>