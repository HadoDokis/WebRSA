<?php
    class Prestform extends AppModel
    {
        var $name = 'Prestform';
        var $useTable = 'prestsform';


        var $belongsTo = array(
            'Actioninsertion' => array(
                'classname' => 'Actioninsertion',
                'foreignKey' => 'actioninsertion_id',
            ),
            'Refpresta' => array(
                'classname' => 'Refpresta',
                'foreignKey' => 'refpresta_id',
            )
        );


        var $validate = array(
            'nomrefpresta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'lib_presta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'date_presta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'date',
                    'message' => 'Champ obligatoire'
                )
            )
        );

    }
?>
