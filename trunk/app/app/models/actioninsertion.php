<?php
    class Actioninsertion extends AppModel
    {
        var $name = 'Actioninsertion';
        var $useTable = 'actionsinsertion';


//         var $hasAndBelongsToMany = array(
//             'Contratinsertion' => array(
//                 'classname' => 'Contratinsertion',
//                 'joinTable' => 'actionsinsertion_liees',
//                 'foreignKey' => 'actioninsertion_id',
//                 'associationForeignKey' => 'contratinsertion_id'
//             )
//         );
        var $belongsTo = array(
            'Contratinsertion' => array(
                'classname' => 'Contratinsertion',
                'foreignKey' => 'contratinsertion_id',
            )
        );

        var $hasMany = array(
            'Aidedirecte' => array(
                'classname' => 'Aidedirecte',
                'foreignKey' => 'actioninsertion_id',
                'dependent' => true
            ),
            'Prestform' => array(
                'classname' => 'Prestform',
                'foreignKey' => 'actioninsertion_id',
                'dependent' => true
            )
        );

//         var $validate = array(
//             'lib_action' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'lib_aide' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'date_aide' => array(
//                 array(
//                     'rule' => 'date',
//                     'message' => 'Veuillez entrer une date valide.'
//                 )
//             ),
//             'lib_presta' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             )
//         );
    }
?>
