<?php
    class Aidedirecte extends AppModel
    {
        var $name = 'Aidedirecte';
        var $useTable = 'aidesdirectes';


        var $belongsTo = array(
            'Actioninsertion' => array(
                'classname' => 'Actioninsertion',
                'foreignKey' => 'actioninsertion_id',
            )
        );

        var $validate = array(
            'lib_aide' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'date_aide' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typo_aide' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),

        );
    }
?>