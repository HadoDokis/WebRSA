<?php
    class Dspf extends AppModel
    {
        var $name = 'Dspf';

        var $useTable = 'dspfs';

        var $belongsTo = array(
            'Foyer' => array(
             'classname'     => 'Foyer',
             'foreignKey'    => 'foyer_id'
            )
        );

        var $hasAndBelongsToMany = array(
            'Nataccosocfam' => array(
                'classname' => 'Nataccosocfam',
                'joinTable' => 'dspfs_nataccosocfams',
                'foreignKey' => 'dspf_id',
                'associationForeignKey' => 'nataccosocfam_id'
            ),
            'Diflog' => array(
                'classname' => 'Diflog',
                'joinTable' => 'dspfs_diflogs',
                'foreignKey' => 'dspf_id',
                'associationForeignKey' => 'diflog_id'
            )
        );


        var $validate = array(
//             'motidemrsa' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'accosocfam' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'nataccosocfam' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'natlog' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
//             'demarlog' => array(
//                 'notEmpty' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//             ),
//             'Diflog' => array(
//                 'notEmpty' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//             )

        );
    }
?>
