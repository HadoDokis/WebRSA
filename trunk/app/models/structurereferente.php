<?php 
    class structurereferente extends AppModel
    {
        var $name = 'Structurereferente';
        var $useTable = 'structuresreferentes';

        function list1Options() {
            $options = $this->find( 
                'list',
                array (
                    'fields' => array(
                        'Structurereferente.id',
                        'Structurereferente.lib_struc'
                    ),
                    'order'  => array( 'Structurereferente.lib_struc ASC' )
                )
            );
            return $options;
        }

        var $hasAndBelongsToMany = array(
            'Zonegeographique' => array(
                'classname'             => 'Zonegeographique',
                'joinTable'             => 'structuresreferentes_zonesgeographiques',
                'foreignKey'            => 'structurereferente_id',
                'associationForeignKey' => 'zonegeographique_id'
            )
        );

        var $belongsTo = array(
            'Typeorient' => array(
                'classname' => 'Typeorient',
                'foreignKey' => 'typeorient_id'
            )
        );

        var $hasMany = array(
            'Referent' => array(
                'classname' => 'Referent',
                'foreignKey' => 'structurereferente_id'
            ),
            'Orientstruct' => array(
                'classname' => 'Orientstruct',
                'foreignKey' => 'structurereferente_id'
            )
        );

        var $validate = array(
            'type_totalisation' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotsoclrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotsoclmajorsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotlocalrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );
    }

?>