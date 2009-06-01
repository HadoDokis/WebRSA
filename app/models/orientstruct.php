<?php
    class Orientstruct extends AppModel
    {
        var $name = 'Orientstruct';
        var $useTable = 'orientsstructs';

        // ********************************************************************

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            )
        );

        // ********************************************************************

        var $validate = array(
            'structurereferente_id' => array(
                array(
                    'rule' => array( 'choixStructure', 'statut_orient' ),
                    'message' => 'Champ obligatoire'
                )
            )
        );

        // --------------------------------------------------------------------

        function choixStructure( $field = array(), $compare_field = null ) {
            foreach( $field as $key => $value ) {
                if( ( $this->data[$this->name][ $compare_field ] != 'En attente' ) && empty( $value ) ) {
                    return false;
                }
            }
            return true;
        }
    }
?>