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
            'dfderact' => array(
                array(
                    'rule'          => 'date',
                    'message'       => 'Veuillez entrer une date valide',
                    'allowEmpty'    => true
                )
            ),
            'annderdipobt' => array(
                array(
                    'rule'          => 'date',
                    'message'       => 'Veuillez entrer une date valide',
                    'allowEmpty'    => true
                )
            )
        );
    }
?>
