<?php
    class Detaildroitrsa extends AppModel
    {
        var $name = 'Detaildroitrsa';
        var $useTable = 'detailsdroitsrsa';

        //*********************************************************************

        var $belongsTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasMany = array(
            'Detailcalculdroitrsa' => array(
                'classname'     => 'Detailcalculdroitrsa',
                'foreignKey'    => 'detaildroitrsa_id'
            )
        );
        //*********************************************************************

        var $validate = array(
            'topsansdomfixe' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtoridemrsa' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),

            'topfoydrodevorsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nbenfautcha' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
	    'oridemrsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'ddelecal' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dfelecal' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

    }
?>