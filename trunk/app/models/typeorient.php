<?php
    class Typeorient extends AppModel
    {
        var $name = 'Typeorient';
        var $useTable = 'typesorients';

        var $hasMany = array(
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'typeorient_id'
            )
        );

        var $validate = array(
            'lib_type_orient' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'modele_notif' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
        );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function listOptions() {
            $options = $this->find(
                'list',
                array (
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array( 'Typeorient.parentid' => NULL ),
                    'order'  => array( 'Typeorient.lib_type_orient ASC' )
                )
            );

            if( $this->find( 'count', array( 'conditions' => array( 'Typeorient.parentid NOT' => NULL ) ) ) > 0 ) {
                $list = array();
                foreach( $options as $key => $option ) {
                    $innerOptions = $this->find( 
                        'list',
                        array (
                            'fields' => array(
                                'Typeorient.id',
                                'Typeorient.lib_type_orient'/*,
                                'Typeorient.parentid'*/
                            ),
                            'conditions' => array( 'Typeorient.parentid' => $key ),
                            'order'  => array( 'Typeorient.lib_type_orient ASC' )
                        )
                    );
                    $list[$option] = $innerOptions ;
                }
                return $list;
            }
            else {
                return $options;
            }
        }

        /** ********************************************************************
        *   Recherche du type d'orientation qui n'a plus de parent
        *** *******************************************************************/

        function getIdLevel0( $typeorient_id ) {
            $tmpTypeorient = $this->find(
                'first',
                array(
                    'fields' => array( 'Typeorient.id', 'Typeorient.parentid' ),
                    'recursive' => -1,
                    'conditions' => array(
                        'Typeorient.id' => $typeorient_id
                    )
                )
            );
            if( !empty( $tmpTypeorient ) ) {
                while( $parentid = Set::classicExtract( $tmpTypeorient, 'Typeorient.parentid' ) ) {
                    $tmpTypeorient = $this->find(
                        'first',
                        array(
                            'fields' => array( 'Typeorient.id', 'Typeorient.parentid' ),
                            'recursive' => -1,
                            'conditions' => array(
                                'Typeorient.id' => $parentid
                            )
                        )
                    );
                }
            }
            if( !empty( $tmpTypeorient ) ) {
                $typeorient_niv1_id = Set::classicExtract( $tmpTypeorient, 'Typeorient.id' );
                if( !empty( $typeorient_niv1_id ) ) {
                    return $typeorient_niv1_id;
                }
            }
            return null;
        }

    }
?>
