<?php
    class Nivetu extends AppModel
    {
        var $name = 'Nivetu';

//         var $validate = array(
//             'code' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire (where ...)'
//             )
//         );
        var $validate = array(
            'Nivetu' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire (where ...)'
            )
        );
    }
?>
