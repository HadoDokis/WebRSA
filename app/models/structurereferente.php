<?php
    class Structurereferente extends AppModel
    {
        var $name = 'Structurereferente';
        var $useTable = 'structuresreferentes';
        var $displayField = 'lib_struc';
        var $order = array( 'lib_struc ASC' );

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


        /** ********************************************************************
        *
        *** *******************************************************************/

        function listOptions() {
            //$typesorients = $this->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ), 'conditions' => array( 'Typeorient.parentid IS NULL' ), 'order' => array( 'Typeorient.lib_type_orient ASC' ) ) );

            $list = array();
            if( Configure::read( 'with_parentid' ) == true ) {
                $typesorients = $this->Typeorient->find( 'all', array( 'conditions' => array( 'Typeorient.parentid IS NOT NULL' ), 'order' => array( 'Typeorient.lib_type_orient ASC' ) ) );
            }
            else {
                $typesorients = $this->Typeorient->find( 'all', array( /*'conditions' => array( 'Typeorient.parentid IS NOT NULL' ),*/ 'order' => array( 'Typeorient.lib_type_orient ASC' ) ) );
            }

            foreach( $typesorients as $typeorient ) {
                $optgroup = Set::classicExtract( $typeorient, 'Typeorient.lib_type_orient' );
                $structures = Set::combine( $typeorient, 'Structurereferente.{n}.id', 'Structurereferente.{n}.lib_struc' );

                if( !empty( $structures ) ) {
                    if( !empty( $typeorient ) ) {
                        $list[$optgroup] = $structures;
                    }
                }
            }
            return $list;

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
            ),
            'Permanence' => array(
                'classname' => 'Permanence',
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