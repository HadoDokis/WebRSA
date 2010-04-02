<?php
    class Tiersprestataireapre extends AppModel
    {
        var $name = 'Tiersprestataireapre';
        var $useTable = 'tiersprestatairesapres';

        var $displayField = 'full_name';

        var $actsAs = array(
            'Enumerable', // FIXME ?
            'MultipleDisplayFields' => array(
                'fields' => array( 'nomtiers' ),
                'pattern' => '%s'
            )
        );

        var $order = 'Tiersprestataireapre.id ASC';


        var $hasMany = array(
            'Formqualif' => array(
                'classname' => 'Formqualif',
                'foreignKey' => 'tiersprestataireapre_id',
            ),
            'Formpermfimo' => array(
                'classname' => 'Formpermfimo',
                'foreignKey' => 'tiersprestataireapre_id',
            ),
            'Actprof' => array(
                'classname' => 'Actprof',
                'foreignKey' => 'tiersprestataireapre_id',
            ),
            'Permisb' => array(
                'classname' => 'Permisb',
                'foreignKey' => 'tiersprestataireapre_id',
            )
        );


        var $validate = array(
            'nomtiers' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'siret' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'isUnique',
                    'message' => 'Ce numéro SIRET existe déjà'
                )
            ),
//             'numvoie' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
            'typevoie' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'nomvoie' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'codepos' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'ville' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'numtel' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
//             'adrelec' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
            'nomtiturib' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'etaban' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'guiban' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'numcomptban' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'clerib' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'aidesliees' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
        );

    }
?>