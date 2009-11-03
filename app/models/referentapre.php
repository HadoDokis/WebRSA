<?php
    class Referentapre extends AppModel
    {
        var $name = 'Referentapre';
        var $useTable = 'referentsapre';
//         var $displayField = 'nom';
        var $order = 'Referentapre.id ASC';

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
            'prenom' => array(
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
            }
            return $referents;
        }
    }
?>