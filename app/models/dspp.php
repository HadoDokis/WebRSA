<?php
    class Dspp extends AppModel
    {
        var $name = 'Dspp';

        var $useTable = 'dspps';

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );

        var $hasAndBelongsToMany = array(
            'Difsoc' => array(
                'classname' => 'Difsoc',
                'joinTable' => 'dspps_difsocs',
                'foreignKey' => 'dspp_id',
                'associationForeignKey' => 'difsoc_id'
            ),
            'Nataccosocindi' => array(
                'classname' => 'Nataccosocindi',
                'joinTable' => 'dspps_nataccosocindis',
                'foreignKey' => 'dspp_id',
                'associationForeignKey' => 'nataccosocindi_id'
            ),
            'Difdisp' => array(
                'classname' => 'Difdisp',
                'joinTable' => 'dspps_difdisps',
                'foreignKey' => 'dspp_id',
                'associationForeignKey' => 'difdisp_id'
            ),
            'Natmob' => array(
                'classname' => 'Natmob',
                'joinTable' => 'dspps_natmobs',
                'foreignKey' => 'dspp_id',
                'associationForeignKey' => 'natmob_id',
                //'unique' => true
            ),
            'Nivetu' => array(
                'classname' => 'Nivetu',
                'joinTable' => 'dspps_nivetus',
                'foreignKey' => 'dspp_id',
                'associationForeignKey' => 'nivetu_id'
            ),
            'Accoemploi' => array(
                'classname' => 'Accoemploi',
                'joinTable' => 'dspps_accoemplois',
                'foreignKey' => 'dspp_id',
                'associationForeignKey' => 'accoemploi_id'
            )
        );


        var $validate = array(
            'libautrdifsoc' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'elopersdifdisp' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'obstemploidifdisp' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'soutdemarsoc' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'libautraccosocindi' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
            ),
//             'accoemploi' => array(
//                 'notEmpty' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//             ),
            'hispro' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
            ),
//             'libcooraccosocindi' => array(
//                 'notEmpty' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//             ),
            'duractdomi' => array(
//                 'notEmpty' => array(
                    'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
            ),
            'dfderact' => array(
//                 'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
//                 ),
            ),
            'annderdipobt' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                ),
            )

        );
    }
?>
