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
                if( !empty( $this->data[$this->name][$compare_field] ) && ( $this->data[$this->name][$compare_field] != 'En attente' ) && empty( $value ) ) {
                    return false;
                }
            }
            return true;
        }

        // ********************************************************************

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );
// debug( $this->data );
            if( array_key_exists( 'typeorient_id', $this->data['Orientstruct'] ) ) { // INFO: 1 seul enregistrement
                $this->data['Orientstruct']['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct']['structurereferente_id'] );
            }
            else { // INFO: plusieurs enregistrements
                foreach( $this->data['Orientstruct'] as $key => $value ) {
                    $this->data['Orientstruct'][$key]['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['structurereferente_id'] );
                }
            }
// debug( $this->data );
            return $return;
        }
    }
?>