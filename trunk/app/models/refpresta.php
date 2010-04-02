<?php
    class Refpresta extends AppModel
    {
        var $name = 'Refpresta';
        var $useTable = 'refsprestas';


        var $hasMany = array(
            'Prestform' => array(
                'classname' => 'Prestform',
                'foreignKey' => 'refpresta_id',
            )
        );


//         var $validate = array(
//             'nomrefpresta' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             )
//         );

    }
?>