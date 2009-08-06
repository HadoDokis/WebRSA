<?php
    class Situationdossierrsa extends AppModel
    {
        var $name = 'Situationdossierrsa';
        var $useTable = 'situationsdossiersrsa';

        //*********************************************************************

        var $belongsTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasMany = array(
            'Suspensiondroit' => array(
                'classname'     => 'Suspensiondroit',
                'foreignKey'    => 'situationdossierrsa_id'
            ),
            'Suspensionversement' => array(
                'classname'     => 'Suspensionversement',
                'foreignKey'    => 'situationdossierrsa_id'
            ),
        );
        //*********************************************************************

        var $validate = array(
            'etatdosrsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtrefursa' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'moticlorsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtclorsa' => array(
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