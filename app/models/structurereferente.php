<?php
    class Structurereferente extends AppModel
    {
        var $name = 'Structurereferente';
        var $useTable = 'structuresreferentes';

        function list1Options() {
            $tmp = $this->find(
                'all',
                array (
                    'fields' => array(
                        'Structurereferente.id',
                        'Structurereferente.typeorient_id',
                        'Structurereferente.lib_struc'
                    ),
                    'order'  => array( 'Structurereferente.lib_struc ASC' ),
                    'recursive' => -1
                )
            );

            $return = array();
            foreach( $tmp as $key => $value ) {
                $return[$value['Structurereferente']['typeorient_id'].'_'.$value['Structurereferente']['id']] = $value['Structurereferente']['lib_struc'];
            }
            return $return;
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
            'lib_struc' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'num_voie' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'type_voie' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'nom_voie' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'code_postal' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'ville' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'code_insee' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'typeorient_id'=> array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );
    }

?>