<?php
    class Referentapre extends AppModel
    {
        var $name = 'Referentapre';
        var $useTable = 'referentsapre';
/*
        var $actsAs = array(
//             'Enumerable',
            'MultipleDisplayFields' => array(
                'fields' => array( 'qual', 'nom', 'prenom' )
            )
        );*/

        var $displayField = 'full_name';

        var $actsAs = array(
            'MultipleDisplayFields' => array(
                'fields' => array( 'qual', 'nom', 'prenom' ),
                'pattern' => '%s %s %s'
            )
        );

        var $order = 'Referentapre.id ASC';


        var $enumFields = array(
            'spe' => array( 'type' => 'no', 'domain' => 'default' )
        );

        var $hasMany = array(
            'Apre' => array(
                'classname' => 'Apre',
                'foreignKey' => 'referentapre_id'
            )
        );


        var $validate = array(
            'nom' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'qual' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'prenom' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'adresse' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'numtel' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'email' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'fonction' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'organismeref' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'spe' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );

        function _referentsApre( $apre_id = null ){
            $conditions = array();

            $refsapre = $this->find(
                'all',
                array(
                    'recursive' => -1,
                    'fields' => array( 'Referentapre.id', 'Referentapre.qual', 'Referentapre.nom', 'Referentapre.prenom' ),
                    'conditions' => $conditions
                )
            );

            if( !empty( $refsapre ) ) {
                $ids = Set::extract( $refsapre, '/Referentapre/id' );
                $values = Set::format( $refsapre, '{0} {1} {2}', array( '{n}.Referentapre.qual', '{n}.Referentapre.nom', '{n}.Referentapre.prenom' ) );
                $referents = array_combine( $ids, $values );
                return $referents;
            }

        }
    }
?>