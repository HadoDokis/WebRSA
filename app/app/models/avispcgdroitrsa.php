<?php
    class Avispcgdroitrsa extends AppModel
    {
        var $name = 'Avispcgdroitrsa';
        var $useTable = 'avispcgdroitrsa';

        //*********************************************************************

        var $belongsTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'id'
            )
        );

        var $hasMany = array(
            'Condadmin' => array(
                'classname'     => 'Condadmin',
                'foreignKey'    => 'avispcgdroitrsa_id'
            ),
            'Reducrsa' => array(
                'classname'     => 'Reducrsa',
                'foreignKey'    => 'avispcgdroitrsa_id'
            ),
        );
        //*********************************************************************

        var $validate = array(
            'avisdestpairsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtavisdestpairsa' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nomtie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeperstie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

    }
?>